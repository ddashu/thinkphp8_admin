<template>
  <div class="page-container">
    <!-- Search Bar -->
    <div class="search-bar">
      <el-form :model="searchForm" inline size="small">
        <el-form-item label="渠道名称">
          <el-input v-model="searchForm.name" placeholder="搜索渠道名称" clearable style="width: 180px" />
        </el-form-item>
        <el-form-item label="状态">
          <el-select v-model="searchForm.status" placeholder="全部" clearable style="width: 120px">
            <el-option label="启用" :value="1" />
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
        <h3 class="table-title">短信渠道列表</h3>
        <el-button type="primary" size="small" icon="el-icon-plus" @click="handleAdd">新增渠道</el-button>
      </div>

      <el-table v-loading="loading" :data="tableData" border stripe>
        <el-table-column prop="id" label="ID" width="70" align="center" />
        <el-table-column prop="name" label="渠道名称" min-width="130" />
        <el-table-column prop="code" label="渠道编码" width="110" align="center">
          <template slot-scope="{ row }">
            <el-tag size="small" :type="row.code === 'alibaba' ? '' : 'warning'">{{ row.code === 'alibaba' ? '阿里云' : '腾讯云' }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="sign_name" label="签名" min-width="120" />
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
        <el-table-column prop="is_default" label="是否默认" width="100" align="center">
          <template slot-scope="{ row }">
            <el-tag size="small" :type="row.is_default === 1 ? 'primary' : 'info'">{{ row.is_default === 1 ? '是' : '否' }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="今日用量" width="160" align="center">
          <template slot-scope="{ row }">
            <div class="usage-cell">
              <el-progress
                :percentage="getUsagePercent(row)"
                :stroke-width="8"
                :show-text="false"
                :color="getUsageColor(row)"
                style="flex: 1"
              />
              <span class="usage-text">{{ row.used_today || 0 }}/{{ row.daily_limit || 0 }}</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column prop="create_time" label="创建时间" width="170" />
        <el-table-column label="操作" width="220" align="center" fixed="right">
          <template slot-scope="{ row }">
            <el-button type="text" size="small" icon="el-icon-edit" @click="handleEdit(row)">编辑</el-button>
            <el-button type="text" size="small" icon="el-icon-star-off" @click="handleSetDefault(row)" :disabled="row.is_default === 1">设为默认</el-button>
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
    <el-dialog :title="dialogTitle" :visible.sync="dialogVisible" width="560px" :close-on-click-modal="false">
      <el-form ref="form" :model="form" :rules="formRules" label-width="120px" size="small">
        <el-form-item label="渠道名称" prop="name">
          <el-input v-model="form.name" placeholder="请输入渠道名称" />
        </el-form-item>
        <el-form-item label="渠道编码" prop="code">
          <el-select v-model="form.code" placeholder="请选择渠道类型" style="width: 100%" :disabled="isEdit">
            <el-option label="阿里云短信(alibaba)" value="alibaba" />
            <el-option label="腾讯云短信(tencent)" value="tencent" />
          </el-select>
        </el-form-item>
        <el-form-item label="AccessKey ID" prop="access_key_id">
          <el-input v-model="form.access_key_id" placeholder="请输入AccessKey ID" />
        </el-form-item>
        <el-form-item label="AccessKey Secret" prop="access_key_secret">
          <el-input v-model="form.access_key_secret" type="password" placeholder="请输入AccessKey Secret" show-password />
        </el-form-item>
        <el-form-item label="签名" prop="sign_name">
          <el-input v-model="form.sign_name" placeholder="请输入短信签名" />
        </el-form-item>
        <el-form-item label="模板编码" prop="template_code">
          <el-input v-model="form.template_code" placeholder="请输入短信模板编码" />
        </el-form-item>
        <el-form-item label="接口地址" prop="api_endpoint">
          <el-input v-model="form.api_endpoint" placeholder="请输入接口地址（可选）" />
        </el-form-item>
        <el-form-item label="每日上限" prop="daily_limit">
          <el-input-number v-model="form.daily_limit" :min="0" :max="99999" />
          <span class="form-tip">0表示不限制</span>
        </el-form-item>
        <el-form-item label="是否默认">
          <el-switch v-model="form.is_default" :active-value="1" :inactive-value="0" active-color="#4C6EF5" inactive-color="#E2E2EA" />
        </el-form-item>
        <el-form-item label="状态">
          <el-switch v-model="form.status" :active-value="1" :inactive-value="0" active-color="#40C057" inactive-color="#E2E2EA" />
        </el-form-item>
        <el-form-item label="备注">
          <el-input v-model="form.remark" type="textarea" :rows="2" placeholder="请输入备注信息" />
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
import { getSmsConfigList, createSmsConfig, updateSmsConfig, deleteSmsConfig, updateSmsStatus, setDefaultSms } from '@/api/sms'
import Pagination from '@/components/Pagination/index.vue'

export default {
  name: 'SmsConfig',
  components: { Pagination },
  data() {
    return {
      loading: false,
      submitLoading: false,
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
        code: 'alibaba',
        access_key_id: '',
        access_key_secret: '',
        sign_name: '',
        template_code: '',
        api_endpoint: '',
        daily_limit: 1000,
        is_default: 0,
        status: 1,
        remark: ''
      },
      formRules: {
        name: [{ required: true, message: '请输入渠道名称', trigger: 'blur' }],
        code: [{ required: true, message: '请选择渠道编码', trigger: 'change' }],
        access_key_id: [{ required: true, message: '请输入AccessKey ID', trigger: 'blur' }],
        access_key_secret: [{ required: true, message: '请输入AccessKey Secret', trigger: 'blur' }],
        sign_name: [{ required: true, message: '请输入签名', trigger: 'blur' }],
        template_code: [{ required: true, message: '请输入模板编码', trigger: 'blur' }]
      }
    }
  },
  computed: {
    dialogTitle() {
      return this.isEdit ? '编辑短信渠道' : '新增短信渠道'
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
      getSmsConfigList(params).then(res => {
        this.tableData = res.data.list || []
        this.total = res.data.total || 0
      }).catch(() => {
        this.tableData = []
        this.total = 0
      }).finally(() => {
        this.loading = false
      })
    },
    getUsagePercent(row) {
      const limit = row.daily_limit || 0
      const used = row.used_today || 0
      if (limit <= 0) return 0
      return Math.min(Math.round((used / limit) * 100), 100)
    },
    getUsageColor(row) {
      const percent = this.getUsagePercent(row)
      if (percent >= 90) return '#FA5252'
      if (percent >= 70) return '#FAB005'
      return '#40C057'
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
      this.form = {
        id: undefined,
        name: '',
        code: 'alibaba',
        access_key_id: '',
        access_key_secret: '',
        sign_name: '',
        template_code: '',
        api_endpoint: '',
        daily_limit: 1000,
        is_default: 0,
        status: 1,
        remark: ''
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
        const action = this.isEdit ? updateSmsConfig : createSmsConfig
        const args = this.isEdit ? [this.form.id, this.form] : [this.form]
        action(...args).then(() => {
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
      updateSmsStatus(row.id, newStatus).then(() => {
        this.$message.success('状态已更新')
        row.status = newStatus
      }).catch(() => {})
    },
    handleSetDefault(row) {
      setDefaultSms(row.id).then(() => {
        this.$message.success('已设为默认渠道')
        this.loadData()
      }).catch(() => {})
    },
    handleDelete(row) {
      this.$confirm('确定删除短信渠道"' + row.name + '"吗？此操作不可恢复。', '确认删除', {
        confirmButtonText: '删除',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteSmsConfig(row.id).then(() => {
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

.usage-cell {
  display: flex;
  align-items: center;
  gap: 8px;
}

.usage-text {
  font-size: 12px;
  color: $text-secondary;
  white-space: nowrap;
  min-width: 70px;
  text-align: right;
}

.form-tip {
  margin-left: 8px;
  font-size: 12px;
  color: $text-secondary;
}
</style>
