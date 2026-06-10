<template>
  <div class="page-container">
    <!-- Search Bar -->
    <div class="search-bar">
      <el-form :model="searchForm" inline size="small">
        <el-form-item label="密钥名称">
          <el-input v-model="searchForm.name" placeholder="搜索密钥名称" clearable style="width: 180px" />
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
        <h3 class="table-title">API密钥列表</h3>
        <el-button type="primary" size="small" icon="el-icon-plus" @click="handleAdd">创建密钥</el-button>
      </div>

      <el-table v-loading="loading" :data="tableData" border stripe>
        <el-table-column prop="id" label="ID" width="70" align="center" />
        <el-table-column prop="name" label="密钥名称" min-width="140" />
        <el-table-column prop="key" label="API Key" min-width="180">
          <template slot-scope="{ row }">
            <div class="key-cell">
              <code class="key-code">{{ showFullKey[row.id] ? row.key : maskKey(row.key) }}</code>
              <el-button type="text" size="mini" @click="toggleKeyVisibility(row)">
                <i :class="showFullKey[row.id] ? 'el-icon-hide' : 'el-icon-view'" />
              </el-button>
              <el-button type="text" size="mini" @click="copyKey(row.key)">
                <i class="el-icon-document-copy" />
              </el-button>
            </div>
          </template>
        </el-table-column>
        <el-table-column prop="secret" label="API Secret" min-width="180">
          <template slot-scope="{ row }">
            <code class="key-code">{{ maskKey(row.secret) }}</code>
          </template>
        </el-table-column>
        <el-table-column prop="rate_limit" label="调用限额" width="110" align="center" />
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
        <el-table-column prop="expire_time" label="过期时间" width="170" />
        <el-table-column prop="create_time" label="创建时间" width="170" />
        <el-table-column label="操作" width="140" align="center" fixed="right">
          <template slot-scope="{ row }">
            <el-button type="text" size="small" icon="el-icon-edit" @click="handleEdit(row)">编辑</el-button>
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

    <!-- Create/Edit Dialog -->
    <el-dialog :title="dialogTitle" :visible.sync="dialogVisible" width="520px" :close-on-click-modal="false">
      <el-form ref="form" :model="form" :rules="formRules" label-width="100px" size="small">
        <el-form-item label="密钥名称" prop="name">
          <el-input v-model="form.name" placeholder="例如 生产密钥" />
        </el-form-item>
        <el-form-item label="调用限额" prop="rate_limit">
          <el-input-number v-model="form.rate_limit" :min="0" :max="100000" />
          <span class="form-tip">每分钟请求数，0表示不限</span>
        </el-form-item>
        <el-form-item label="过期时间" prop="expire_time">
          <el-date-picker v-model="form.expire_time" type="datetime" placeholder="请选择过期时间" value-format="yyyy-MM-dd HH:mm:ss" style="width: 100%" />
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
  </div>
</template>

<script>
import { getApiKeyList, createApiKey, updateApiKey, deleteApiKey, updateApiKeyStatus } from '@/api/apikey'
import Pagination from '@/components/Pagination/index.vue'

export default {
  name: 'ApiKeyManagement',
  components: { Pagination },
  data() {
    return {
      loading: false,
      submitLoading: false,
      tableData: [],
      total: 0,
      showFullKey: {},
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
        rate_limit: 60,
        expire_time: '',
        description: '',
        status: 1
      },
      formRules: {
        name: [{ required: true, message: '请输入密钥名称', trigger: 'blur' }]
      }
    }
  },
  computed: {
    dialogTitle() {
      return this.isEdit ? '编辑API密钥' : '创建API密钥'
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
      getApiKeyList(params).then(res => {
        this.tableData = res.data.list || []
        this.total = res.data.total || 0
      }).catch(() => {
        this.tableData = []
        this.total = 0
      }).finally(() => {
        this.loading = false
      })
    },
    maskKey(key) {
      if (!key) return '****'
      if (key.length <= 8) return '****'
      return key.substring(0, 4) + '****' + key.substring(key.length - 4)
    },
    toggleKeyVisibility(row) {
      this.$set(this.showFullKey, row.id, !this.showFullKey[row.id])
    },
    copyKey(key) {
      const textarea = document.createElement('textarea')
      textarea.value = key
      document.body.appendChild(textarea)
      textarea.select()
      document.execCommand('copy')
      document.body.removeChild(textarea)
      this.$message.success('已复制到剪贴板')
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
      this.form = { id: undefined, name: '', rate_limit: 60, expire_time: '', description: '', status: 1 }
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
        const action = this.isEdit ? updateApiKey : createApiKey
        action(this.form).then(() => {
          this.$message.success(this.isEdit ? '更新成功' : '创建成功')
          this.dialogVisible = false
          this.loadData()
        }).catch(() => {}).finally(() => { this.submitLoading = false })
      })
    },
    handleStatusChange(row) {
      const newStatus = row.status === 1 ? 0 : 1
      updateApiKeyStatus(row.id, newStatus).then(() => {
        this.$message.success('状态已更新')
        row.status = newStatus
      }).catch(() => {})
    },
    handleDelete(row) {
      this.$confirm('确定删除API密钥"' + row.name + '"吗？', '确认', {
        confirmButtonText: '删除', cancelButtonText: '取消', type: 'warning'
      }).then(() => {
        deleteApiKey(row.id).then(() => {
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

.key-cell {
  display: flex;
  align-items: center;
  gap: 4px;
}

.key-code {
  font-family: 'Courier New', monospace;
  font-size: 12px;
  background: $bg-page;
  padding: 2px 8px;
  border-radius: 4px;
  color: $text-primary;
}

.form-tip {
  margin-left: 8px;
  font-size: 12px;
  color: $text-secondary;
}
</style>
