<template>
  <div class="page-container">
    <div class="content-card">
      <div class="table-toolbar">
        <h3 class="table-title">菜单管理</h3>
        <el-button type="primary" size="small" icon="el-icon-plus" @click="handleAdd(0)">新增根菜单</el-button>
      </div>

      <el-table v-loading="loading" :data="menuTree" row-key="id" border :tree-props="{ children: 'children', hasChildren: 'hasChildren' }">
        <el-table-column prop="name" label="菜单名称" min-width="180" />
        <el-table-column prop="icon" label="图标" width="80" align="center">
          <template slot-scope="{ row }">
            <i v-if="row.icon" :class="row.icon" style="font-size: 18px" />
            <span v-else>-</span>
          </template>
        </el-table-column>
        <el-table-column prop="type" label="类型" width="100" align="center">
          <template slot-scope="{ row }">
            <el-tag v-if="row.type === 1" size="mini" type="">目录</el-tag>
            <el-tag v-else-if="row.type === 2" size="mini" type="success">菜单</el-tag>
            <el-tag v-else-if="row.type === 3" size="mini" type="warning">按钮</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="path" label="路由路径" min-width="140" show-overflow-tooltip />
        <el-table-column prop="component" label="组件路径" min-width="140" show-overflow-tooltip />
        <el-table-column prop="sort" label="排序" width="70" align="center" />
        <el-table-column prop="status" label="状态" width="80" align="center">
          <template slot-scope="{ row }">
            <el-tag v-if="row.status === 1" size="mini" type="success">正常</el-tag>
            <el-tag v-else size="mini" type="danger">禁用</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="操作" width="220" align="center">
          <template slot-scope="{ row }">
            <el-button v-if="row.type !== 3" type="text" size="small" icon="el-icon-plus" @click="handleAdd(row.id)">新增</el-button>
            <el-button type="text" size="small" icon="el-icon-edit" @click="handleEdit(row)">编辑</el-button>
            <el-button type="text" size="small" icon="el-icon-delete" style="color: #FA5252" @click="handleDelete(row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <!-- Add/Edit Dialog -->
    <el-dialog :title="dialogTitle" :visible.sync="dialogVisible" width="560px" :close-on-click-modal="false">
      <el-form ref="form" :model="form" :rules="formRules" label-width="100px" size="small">
        <el-form-item label="上级菜单" prop="parent_id">
          <el-tree-select
            v-if="false"
            v-model="form.parent_id"
          />
          <el-select v-model="form.parent_id" placeholder="根菜单" clearable style="width: 100%">
            <el-option label="根菜单" :value="0" />
            <el-option v-for="menu in flatMenuList" :key="menu.id" :label="menu.name" :value="menu.id" />
          </el-select>
        </el-form-item>
        <el-form-item label="类型" prop="type">
          <el-radio-group v-model="form.type">
            <el-radio :label="1">目录</el-radio>
            <el-radio :label="2">菜单</el-radio>
            <el-radio :label="3">按钮</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="菜单名称" prop="name">
          <el-input v-model="form.name" placeholder="请输入菜单名称" />
        </el-form-item>
        <el-form-item v-if="form.type !== 3" label="图标" prop="icon">
          <el-input v-model="form.icon" placeholder="例如 el-icon-user">
            <template slot="prepend">
              <i :class="form.icon || 'el-icon-menu'" />
            </template>
          </el-input>
        </el-form-item>
        <el-form-item v-if="form.type !== 3" label="路由路径" prop="path">
          <el-input v-model="form.path" placeholder="例如 /system/user" />
        </el-form-item>
        <el-form-item v-if="form.type === 2" label="组件路径" prop="component">
          <el-input v-model="form.component" placeholder="例如 system/user/index" />
        </el-form-item>
        <el-form-item v-if="form.type === 3" label="权限标识" prop="permission">
          <el-input v-model="form.permission" placeholder="例如 system:user:create" />
        </el-form-item>
        <el-form-item label="排序" prop="sort">
          <el-input-number v-model="form.sort" :min="0" :max="999" />
        </el-form-item>
        <el-form-item label="状态" prop="status">
          <el-radio-group v-model="form.status">
            <el-radio :label="1">正常</el-radio>
            <el-radio :label="0">禁用</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" size="small" :loading="submitLoading" @click="handleSubmit">确定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getMenuTree, getMenuList, createMenu, updateMenu, deleteMenu } from '@/api/menu'

export default {
  name: 'MenuManagement',
  data() {
    return {
      loading: false,
      submitLoading: false,
      menuTree: [],
      flatMenuList: [],
      dialogVisible: false,
      isEdit: false,
      form: {
        id: undefined,
        parent_id: 0,
        type: 1,
        name: '',
        icon: '',
        path: '',
        component: '',
        permission: '',
        sort: 0,
        status: 1
      },
      formRules: {
        name: [{ required: true, message: '请输入菜单名称', trigger: 'blur' }],
        type: [{ required: true, message: '请选择类型', trigger: 'change' }]
      }
    }
  },
  computed: {
    dialogTitle() {
      return this.isEdit ? '编辑菜单' : '新增菜单'
    }
  },
  created() {
    this.loadData()
  },
  methods: {
    loadData() {
      this.loading = true
      getMenuTree().then(res => {
        this.menuTree = res.data || []
        this.flatMenuList = this.flattenMenu(this.menuTree)
      }).catch(() => {
        this.menuTree = []
      }).finally(() => {
        this.loading = false
      })
    },
    flattenMenu(tree, result = []) {
      tree.forEach(item => {
        result.push({ id: item.id, name: item.name })
        if (item.children && item.children.length) {
          this.flattenMenu(item.children, result)
        }
      })
      return result
    },
    handleAdd(parentId) {
      this.isEdit = false
      this.form = {
        id: undefined,
        parent_id: parentId,
        type: parentId === 0 ? 1 : 2,
        name: '',
        icon: '',
        path: '',
        component: '',
        permission: '',
        sort: 0,
        status: 1
      }
      this.dialogVisible = true
      this.$nextTick(() => { this.$refs.form && this.$refs.form.clearValidate() })
    },
    handleEdit(row) {
      this.isEdit = true
      this.form = { ...row }
      this.dialogVisible = true
      this.$nextTick(() => { this.$refs.form && this.$refs.form.clearValidate() })
    },
    handleSubmit() {
      this.$refs.form.validate(valid => {
        if (!valid) return
        this.submitLoading = true
        const action = this.isEdit ? updateMenu : createMenu
        action(this.form).then(() => {
          this.$message.success(this.isEdit ? '更新成功' : '创建成功')
          this.dialogVisible = false
          this.loadData()
        }).catch(() => {}).finally(() => { this.submitLoading = false })
      })
    },
    handleDelete(row) {
      this.$confirm('确定删除菜单"' + row.name + '"吗？', '确认', {
        confirmButtonText: '删除', cancelButtonText: '取消', type: 'warning'
      }).then(() => {
        deleteMenu(row.id).then(() => {
          this.$message.success('删除成功')
          this.loadData()
        })
      }).catch(() => {})
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/styles/variables.scss';

.table-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
}

.table-title {
  font-size: 16px;
  font-weight: 600;
  color: $text-primary;
}
</style>
