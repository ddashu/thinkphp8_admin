<template>
  <div class="page-container">
    <!-- Search Bar -->
    <div class="search-bar">
      <el-form :model="searchForm" inline size="small">
        <el-form-item label="模型名称">
          <el-input v-model="searchForm.name" placeholder="搜索模型名称" clearable style="width: 180px" />
        </el-form-item>
        <el-form-item label="分类">
          <el-select v-model="searchForm.category" placeholder="全部" clearable style="width: 140px">
            <el-option label="对话" value="chat" />
            <el-option label="补全" value="completion" />
            <el-option label="向量" value="embedding" />
            <el-option label="图像" value="image" />
            <el-option label="语音" value="audio" />
          </el-select>
        </el-form-item>
        <el-form-item label="供应商">
          <el-select v-model="searchForm.provider" placeholder="全部" clearable style="width: 140px">
            <el-option label="OpenAI" value="openai" />
            <el-option label="Anthropic" value="anthropic" />
            <el-option label="Google" value="google" />
            <el-option label="Meta" value="meta" />
          </el-select>
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
        <h3 class="table-title">AI模型列表</h3>
        <el-button type="primary" size="small" icon="el-icon-plus" @click="handleAdd">新增模型</el-button>
      </div>

      <el-table v-loading="loading" :data="tableData" border stripe>
        <el-table-column prop="id" label="ID" width="70" align="center" />
        <el-table-column prop="name" label="模型名称" min-width="140" />
        <el-table-column prop="model_id" label="模型编码" min-width="160">
          <template slot-scope="{ row }">
            <code class="model-id">{{ row.model_id }}</code>
          </template>
        </el-table-column>
        <el-table-column prop="category" label="分类" width="110" align="center">
          <template slot-scope="{ row }">
            <el-tag size="small" :type="categoryTagType(row.category)">{{ row.category }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="provider" label="供应商" width="110" align="center">
          <template slot-scope="{ row }">
            <span class="provider-text">{{ row.provider }}</span>
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
        <el-table-column label="操作" width="180" align="center" fixed="right">
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

    <!-- Add/Edit Dialog -->
    <el-dialog :title="dialogTitle" :visible.sync="dialogVisible" width="600px" :close-on-click-modal="false">
      <el-form ref="form" :model="form" :rules="formRules" label-width="110px" size="small">
        <el-form-item label="模型名称" prop="name">
          <el-input v-model="form.name" placeholder="例如 GPT-4" />
        </el-form-item>
        <el-form-item label="模型编码" prop="model_id">
          <el-input v-model="form.model_id" placeholder="例如 gpt-4" />
        </el-form-item>
        <el-form-item label="分类" prop="category">
          <el-select v-model="form.category" placeholder="请选择分类" style="width: 100%">
            <el-option label="对话" value="chat" />
            <el-option label="补全" value="completion" />
            <el-option label="向量" value="embedding" />
            <el-option label="图像" value="image" />
            <el-option label="语音" value="audio" />
          </el-select>
        </el-form-item>
        <el-form-item label="供应商" prop="provider">
          <el-select v-model="form.provider" placeholder="请选择供应商" style="width: 100%">
            <el-option label="OpenAI" value="openai" />
            <el-option label="Anthropic" value="anthropic" />
            <el-option label="Google" value="google" />
            <el-option label="Meta" value="meta" />
            <el-option label="其他" value="other" />
          </el-select>
        </el-form-item>
        <el-form-item label="API端点" prop="api_endpoint">
          <el-input v-model="form.api_endpoint" placeholder="例如 https://api.openai.com/v1/chat/completions" />
        </el-form-item>
        <el-form-item label="最大Token数" prop="max_tokens">
          <el-input-number v-model="form.max_tokens" :min="1" :max="1000000" />
        </el-form-item>
        <el-form-item label="配置(JSON)" prop="config">
          <el-input v-model="form.config" type="textarea" :rows="5" placeholder='{"temperature": 0.7, "top_p": 1}' />
        </el-form-item>
        <el-form-item label="描述" prop="description">
          <el-input v-model="form.description" type="textarea" :rows="2" placeholder="请输入描述" />
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
import { getModelList, createModel, updateModel, deleteModel, updateModelStatus } from '@/api/model'
import Pagination from '@/components/Pagination/index.vue'

export default {
  name: 'ModelManagement',
  components: { Pagination },
  data() {
    return {
      loading: false,
      submitLoading: false,
      tableData: [],
      total: 0,
      searchForm: {
        name: '',
        category: '',
        provider: '',
        status: '',
        page: 1,
        page_size: 20
      },
      dialogVisible: false,
      isEdit: false,
      form: {
        id: undefined,
        name: '',
        model_id: '',
        category: '',
        provider: '',
        api_endpoint: '',
        max_tokens: 4096,
        config: '{}',
        description: '',
        status: 1
      },
      formRules: {
        name: [{ required: true, message: '请输入模型名称', trigger: 'blur' }],
        model_id: [{ required: true, message: '请输入模型编码', trigger: 'blur' }],
        category: [{ required: true, message: '请选择分类', trigger: 'change' }],
        provider: [{ required: true, message: '请选择供应商', trigger: 'change' }]
      }
    }
  },
  computed: {
    dialogTitle() {
      return this.isEdit ? '编辑模型' : '新增模型'
    }
  },
  created() {
    this.loadData()
  },
  methods: {
    categoryTagType(category) {
      const map = { chat: '', completion: 'success', embedding: 'warning', image: 'danger', audio: 'info' }
      return map[category] || ''
    },
    loadData() {
      this.loading = true
      const params = {
        page: this.searchForm.page,
        page_size: this.searchForm.page_size,
        name: this.searchForm.name || undefined,
        category: this.searchForm.category || undefined,
        provider: this.searchForm.provider || undefined,
        status: this.searchForm.status !== '' ? this.searchForm.status : undefined
      }
      getModelList(params).then(res => {
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
      this.searchForm = { name: '', category: '', provider: '', status: '', page: 1, page_size: 20 }
      this.loadData()
    },
    handleAdd() {
      this.isEdit = false
      this.form = { id: undefined, name: '', model_id: '', category: '', provider: '', api_endpoint: '', max_tokens: 4096, config: '{}', description: '', status: 1 }
      this.dialogVisible = true
      this.$nextTick(() => { this.$refs.form && this.$refs.form.clearValidate() })
    },
    handleEdit(row) {
      this.isEdit = true
      this.form = { ...row, config: typeof row.config === 'object' ? JSON.stringify(row.config, null, 2) : (row.config || '{}') }
      this.dialogVisible = true
      this.$nextTick(() => { this.$refs.form && this.$refs.form.clearValidate() })
    },
    handleSubmit() {
      this.$refs.form.validate(valid => {
        if (!valid) return
        let submitData = { ...this.form }
        try {
          if (typeof submitData.config === 'string') {
            submitData.config = JSON.parse(submitData.config)
          }
        } catch (e) {
          this.$message.error('配置JSON格式无效')
          return
        }
        this.submitLoading = true
        const action = this.isEdit ? updateModel : createModel
        action(submitData).then(() => {
          this.$message.success(this.isEdit ? '更新成功' : '创建成功')
          this.dialogVisible = false
          this.loadData()
        }).catch(() => {}).finally(() => { this.submitLoading = false })
      })
    },
    handleStatusChange(row) {
      const newStatus = row.status === 1 ? 0 : 1
      updateModelStatus(row.id, newStatus).then(() => {
        this.$message.success('状态已更新')
        row.status = newStatus
      }).catch(() => {})
    },
    handleDelete(row) {
      this.$confirm('确定删除模型"' + row.name + '"吗？', '确认', {
        confirmButtonText: '删除', cancelButtonText: '取消', type: 'warning'
      }).then(() => {
        deleteModel(row.id).then(() => {
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

.model-id {
  font-family: 'Courier New', monospace;
  font-size: 12px;
  background: $bg-page;
  padding: 2px 8px;
  border-radius: 4px;
  color: $text-primary;
}

.provider-text {
  text-transform: capitalize;
  font-weight: 500;
}
</style>
