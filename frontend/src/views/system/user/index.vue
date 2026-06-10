<template>
  <div class="page-container">
    <!-- Search Bar -->
    <div class="search-bar">
      <el-form :model="searchForm" inline size="small">
        <el-form-item label="用户名">
          <el-input v-model="searchForm.username" placeholder="搜索用户名" clearable style="width: 180px" />
        </el-form-item>
        <el-form-item label="状态">
          <el-select v-model="searchForm.status" placeholder="全部" clearable style="width: 120px">
            <el-option label="正常" :value="1" />
            <el-option label="禁用" :value="0" />
          </el-select>
        </el-form-item>
        <el-form-item label="日期">
          <el-date-picker
            v-model="searchForm.dateRange"
            type="daterange"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            value-format="yyyy-MM-dd"
            style="width: 240px"
          />
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
        <h3 class="table-title">用户列表</h3>
        <el-button type="primary" size="small" icon="el-icon-plus" @click="handleAdd">新增用户</el-button>
      </div>

      <el-table v-loading="loading" :data="tableData" border stripe>
        <el-table-column prop="id" label="ID" width="70" align="center" />
        <el-table-column prop="username" label="用户名" min-width="120" />
        <el-table-column prop="nickname" label="昵称" min-width="120" />
        <el-table-column prop="role_name" label="角色" min-width="100">
          <template slot-scope="{ row }">
            <el-tag size="small" :type="row.role_code === 'admin' ? 'danger' : 'info'">
              {{ row.role_name || '普通用户' }}
            </el-tag>
          </template>
        </el-table-column>
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
        <el-table-column label="操作" width="220" align="center" fixed="right">
          <template slot-scope="{ row }">
            <el-button type="text" size="small" icon="el-icon-edit" @click="handleEdit(row)">编辑</el-button>
            <el-button type="text" size="small" icon="el-icon-key" @click="handleResetPwd(row)">重置密码</el-button>
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
        <el-form-item label="用户名" prop="username">
          <el-input v-model="form.username" placeholder="请输入用户名" :disabled="isEdit" />
        </el-form-item>
        <el-form-item v-if="!isEdit" label="密码" prop="password">
          <el-input v-model="form.password" type="password" placeholder="请输入密码" show-password />
        </el-form-item>
        <el-form-item label="昵称" prop="nickname">
          <el-input v-model="form.nickname" placeholder="请输入昵称" />
        </el-form-item>
        <el-form-item label="角色" prop="role_id">
          <el-select v-model="form.role_id" placeholder="请选择角色" style="width: 100%">
            <el-option v-for="role in roleOptions" :key="role.id" :label="role.name" :value="role.id" />
          </el-select>
        </el-form-item>
        <el-form-item label="邮箱" prop="email">
          <el-input v-model="form.email" placeholder="请输入邮箱" />
        </el-form-item>
        <el-form-item label="手机号" prop="phone">
          <el-input v-model="form.phone" placeholder="请输入手机号" />
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
import { getUserList, createUser, updateUser, deleteUser, updateUserStatus, resetUserPassword } from '@/api/user'
import { getRoleList } from '@/api/role'
import Pagination from '@/components/Pagination/index.vue'

export default {
  name: 'UserManagement',
  components: { Pagination },
  data() {
    return {
      loading: false,
      submitLoading: false,
      tableData: [],
      total: 0,
      searchForm: {
        username: '',
        status: '',
        dateRange: [],
        page: 1,
        page_size: 20
      },
      dialogVisible: false,
      isEdit: false,
      form: {
        id: undefined,
        username: '',
        password: '',
        nickname: '',
        role_id: '',
        email: '',
        phone: '',
        status: 1
      },
      formRules: {
        username: [{ required: true, message: '请输入用户名', trigger: 'blur' }],
        password: [{ required: true, message: '请输入密码', trigger: 'blur' }],
        nickname: [{ required: true, message: '请输入昵称', trigger: 'blur' }],
        role_id: [{ required: true, message: '请选择角色', trigger: 'change' }]
      },
      roleOptions: []
    }
  },
  computed: {
    dialogTitle() {
      return this.isEdit ? '编辑用户' : '新增用户'
    }
  },
  created() {
    this.loadData()
    this.loadRoles()
  },
  methods: {
    loadData() {
      this.loading = true
      const params = {
        page: this.searchForm.page,
        page_size: this.searchForm.page_size,
        username: this.searchForm.username || undefined,
        status: this.searchForm.status !== '' ? this.searchForm.status : undefined,
        start_date: this.searchForm.dateRange && this.searchForm.dateRange[0] || undefined,
        end_date: this.searchForm.dateRange && this.searchForm.dateRange[1] || undefined
      }
      getUserList(params).then(res => {
        this.tableData = res.data.list || []
        this.total = res.data.total || 0
      }).catch(() => {
        this.tableData = []
        this.total = 0
      }).finally(() => {
        this.loading = false
      })
    },
    loadRoles() {
      getRoleList({ page_size: 100 }).then(res => {
        this.roleOptions = res.data.list || []
      }).catch(() => {})
    },
    handleSearch() {
      this.searchForm.page = 1
      this.loadData()
    },
    resetSearch() {
      this.searchForm = {
        username: '',
        status: '',
        dateRange: [],
        page: 1,
        page_size: 20
      }
      this.loadData()
    },
    handleAdd() {
      this.isEdit = false
      this.form = {
        id: undefined,
        username: '',
        password: '',
        nickname: '',
        role_id: '',
        email: '',
        phone: '',
        status: 1
      }
      this.dialogVisible = true
      this.$nextTick(() => {
        this.$refs.form && this.$refs.form.clearValidate()
      })
    },
    handleEdit(row) {
      this.isEdit = true
      this.form = { ...row }
      this.dialogVisible = true
      this.$nextTick(() => {
        this.$refs.form && this.$refs.form.clearValidate()
      })
    },
    handleSubmit() {
      this.$refs.form.validate(valid => {
        if (!valid) return
        this.submitLoading = true
        const action = this.isEdit ? updateUser : createUser
        action(this.form).then(() => {
          this.$message.success(this.isEdit ? '更新成功' : '创建成功')
          this.dialogVisible = false
          this.loadData()
        }).catch(() => {}).finally(() => {
          this.submitLoading = false
        })
      })
    },
    handleStatusChange(row) {
      const newStatus = row.status === 1 ? 0 : 1
      updateUserStatus(row.id, newStatus).then(() => {
        this.$message.success('状态已更新')
        row.status = newStatus
      }).catch(() => {})
    },
    handleResetPwd(row) {
      this.$confirm('确定重置用户"' + row.username + '"的密码吗？', '确认', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        resetUserPassword(row.id).then(() => {
          this.$message.success('密码已重置')
        })
      }).catch(() => {})
    },
    handleDelete(row) {
      this.$confirm('确定删除用户"' + row.username + '"吗？此操作不可恢复。', '确认', {
        confirmButtonText: '删除',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteUser(row.id).then(() => {
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
