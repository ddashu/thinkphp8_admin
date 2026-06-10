<template>
  <div class="page-container">
    <!-- Search Bar -->
    <div class="search-bar">
      <el-form :model="searchForm" inline size="small">
        <el-form-item label="角色名称">
          <el-input v-model="searchForm.name" placeholder="搜索角色名称" clearable style="width: 180px" />
        </el-form-item>
        <el-form-item label="状态">
          <el-select v-model="searchForm.status" placeholder="全部" clearable style="width: 120px">
            <el-option label="正常" :value="1" />
            <el-option label="禁用" :value="0" />
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="handleSearch">搜索</el-button>
          <el-button icon="el-icon-refresh" @click="resetSearch">重置</el-button>
        </el-form-item>
      </el-form>
    </div>

    <!-- Table -->
    <div class="content-card">
      <div class="table-toolbar">
        <h3 class="table-title">角色列表</h3>
        <el-button type="primary" size="small" icon="el-icon-plus" @click="handleAdd">新增角色</el-button>
      </div>

      <el-table v-loading="loading" :data="tableData" border stripe>
        <el-table-column prop="id" label="ID" width="70" align="center" />
        <el-table-column prop="name" label="角色名称" min-width="120" />
        <el-table-column prop="code" label="角色编码" min-width="120" />
        <el-table-column prop="description" label="描述" min-width="180" show-overflow-tooltip />
        <el-table-column prop="status" label="状态" width="100" align="center">
          <template slot-scope="{ row }">
            <el-switch
              :value="row.status === 1"
              active-color="#40C057"
              inactive-color="#E2E2EA"
              @change="handleStatusChange(row)"
            />
          </template>
        </el-table-column>
        <el-table-column prop="create_time" label="创建时间" width="170" />
        <el-table-column label="操作" width="240" align="center" fixed="right">
          <template slot-scope="{ row }">
            <el-button type="text" size="small" icon="el-icon-edit" @click="handleEdit(row)">编辑</el-button>
            <el-button type="text" size="small" icon="el-icon-s-check" @click="handleAssignPerm(row)">权限</el-button>
            <el-button type="text" size="small" icon="el-icon-delete" style="color: #FA5252" @click="handleDelete(row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>

      <pagination
        :total="total"
        :page.sync="searchForm.page"
        :limit.sync="searchForm.page_size"
        @pagination="loadData"
      />
    </div>

    <!-- Add/Edit Dialog -->
    <el-dialog :title="dialogTitle" :visible.sync="dialogVisible" width="520px" :close-on-click-modal="false">
      <el-form ref="form" :model="form" :rules="formRules" label-width="100px" size="small">
        <el-form-item label="角色名称" prop="name">
          <el-input v-model="form.name" placeholder="请输入角色名称" />
        </el-form-item>
        <el-form-item label="角色编码" prop="code">
          <el-input v-model="form.code" placeholder="请输入角色编码" :disabled="isEdit" />
        </el-form-item>
        <el-form-item label="描述" prop="description">
          <el-input v-model="form.description" type="textarea" :rows="3" placeholder="请输入描述" />
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

    <!-- Assign Permissions Dialog -->
    <el-dialog title="分配权限" :visible.sync="permDialogVisible" width="480px" :close-on-click-modal="false">
      <el-tree
        ref="permTree"
        :data="menuTree"
        show-checkbox
        node-key="id"
        :default-checked-keys="checkedKeys"
        :props="{ children: 'children', label: 'name' }"
      />
      <div slot="footer">
        <el-button size="small" @click="permDialogVisible = false">取消</el-button>
        <el-button type="primary" size="small" :loading="permLoading" @click="handlePermSubmit">确定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getRoleList, createRole, updateRole, deleteRole, updateRoleStatus, assignPermissions, getRolePermissions } from '@/api/role'
import { getMenuTree } from '@/api/menu'
import Pagination from '@/components/Pagination/index.vue'

export default {
  name: 'RoleManagement',
  components: { Pagination },
  data() {
    return {
      loading: false,
      submitLoading: false,
      permLoading: false,
      tableData: [],
      total: 0,
      searchForm: {
        name: '',
        status: '',
        page: 1,
        page_size: 20
      },
      dialogVisible: false,
      isEdit: false,
      form: {
        id: undefined,
        name: '',
        code: '',
        description: '',
        status: 1
      },
      formRules: {
        name: [{ required: true, message: '请输入角色名称', trigger: 'blur' }],
        code: [{ required: true, message: '请输入角色编码', trigger: 'blur' }]
      },
      permDialogVisible: false,
      currentRoleId: null,
      menuTree: [],
      checkedKeys: []
    }
  },
  computed: {
    dialogTitle() {
      return this.isEdit ? '编辑角色' : '新增角色'
    }
  },
  created() {
    this.loadData()
  },
  methods: {
    loadData() {
      this.loading = true
      const params = {
        page: this.searchForm.page,
        page_size: this.searchForm.page_size,
        name: this.searchForm.name || undefined,
        status: this.searchForm.status !== '' ? this.searchForm.status : undefined
      }
      getRoleList(params).then(res => {
        this.tableData = res.data.list || []
        this.total = res.data.total || 0
      }).catch(() => {
        this.tableData = []
        this.total = 0
      }).finally(() => {
        this.loading = false
      })
    },
    handleSearch() {
      this.searchForm.page = 1
      this.loadData()
    },
    resetSearch() {
      this.searchForm = { name: '', status: '', page: 1, page_size: 20 }
      this.loadData()
    },
    handleAdd() {
      this.isEdit = false
      this.form = { id: undefined, name: '', code: '', description: '', status: 1 }
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
        const action = this.isEdit ? updateRole : createRole
        action(this.form).then(() => {
          this.$message.success(this.isEdit ? '更新成功' : '创建成功')
          this.dialogVisible = false
          this.loadData()
        }).catch(() => {}).finally(() => { this.submitLoading = false })
      })
    },
    handleStatusChange(row) {
      const newStatus = row.status === 1 ? 0 : 1
      updateRoleStatus(row.id, newStatus).then(() => {
        this.$message.success('状态已更新')
        row.status = newStatus
      }).catch(() => {})
    },
    handleDelete(row) {
      this.$confirm('确定删除角色"' + row.name + '"吗？', '确认', {
        confirmButtonText: '删除', cancelButtonText: '取消', type: 'warning'
      }).then(() => {
        deleteRole(row.id).then(() => {
          this.$message.success('删除成功')
          this.loadData()
        })
      }).catch(() => {})
    },
    handleAssignPerm(row) {
      this.currentRoleId = row.id
      this.checkedKeys = []
      getMenuTree().then(res => {
        this.menuTree = res.data || []
      }).catch(() => {
        this.menuTree = []
      })
      getRolePermissions(row.id).then(res => {
        // 从角色详情中提取已分配的菜单ID列表
        const roleData = res.data
        if (roleData && Array.isArray(roleData.menus)) {
          this.checkedKeys = roleData.menus.map(m => m.id || m.menu_id)
        } else if (Array.isArray(roleData)) {
          this.checkedKeys = roleData.map(m => m.id || m.menu_id)
        } else {
          this.checkedKeys = []
        }
      }).catch(() => {
        this.checkedKeys = []
      })
      this.permDialogVisible = true
    },
    handlePermSubmit() {
      this.permLoading = true
      const checkedKeys = this.$refs.permTree.getCheckedKeys()
      const halfCheckedKeys = this.$refs.permTree.getHalfCheckedKeys()
      const menuIds = checkedKeys.concat(halfCheckedKeys)
      assignPermissions({ id: this.currentRoleId, menu_ids: menuIds }).then(() => {
        this.$message.success('权限已更新')
        this.permDialogVisible = false
      }).catch(() => {}).finally(() => { this.permLoading = false })
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
