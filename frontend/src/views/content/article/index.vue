<template>
  <div class="page-container">
    <!-- Search Bar -->
    <div class="search-bar">
      <el-form :model="searchForm" inline size="small">
        <el-form-item label="标题">
          <el-input v-model="searchForm.title" placeholder="搜索文章标题" clearable style="width: 200px" />
        </el-form-item>
        <el-form-item label="分类">
          <el-cascader
            v-model="searchForm.category_id"
            :options="categoryOptions"
            :props="{ checkStrictly: true, value: 'id', label: 'name', children: 'children', emitPath: false }"
            placeholder="选择分类"
            clearable
            filterable
            style="width: 200px"
          />
        </el-form-item>
        <el-form-item label="状态">
          <el-select v-model="searchForm.status" placeholder="全部" clearable style="width: 120px">
            <el-option label="草稿" value="draft" />
            <el-option label="已发布" value="published" />
            <el-option label="已下架" value="unpublished" />
          </el-select>
        </el-form-item>
        <el-form-item label="发布日期">
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
        <h3 class="table-title">文章列表</h3>
        <el-button type="primary" size="small" icon="el-icon-plus" @click="handleAdd">新增文章</el-button>
      </div>

      <el-table v-loading="loading" :data="tableData" border stripe>
        <el-table-column label="封面图" width="90" align="center">
          <template slot-scope="{ row }">
            <el-image
              v-if="row.cover_image"
              :src="row.cover_image"
              :preview-src-list="[row.cover_image]"
              fit="cover"
              style="width: 60px; height: 40px; border-radius: 4px"
            />
            <span v-else class="no-image">无封面</span>
          </template>
        </el-table-column>
        <el-table-column prop="title" label="标题" min-width="180">
          <template slot-scope="{ row }">
            <el-link type="primary" :underline="false" @click="handleViewDetail(row)">{{ row.title }}</el-link>
          </template>
        </el-table-column>
        <el-table-column prop="category_name" label="分类" width="120" />
        <el-table-column prop="author" label="作者" width="100" />
        <el-table-column prop="status" label="状态" width="90" align="center">
          <template slot-scope="{ row }">
            <el-tag size="small" :type="getStatusType(row.status)">{{ getStatusLabel(row.status) }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="view_count" label="阅读量" width="90" align="center">
          <template slot-scope="{ row }">
            <span class="view-count">{{ row.view_count || 0 }}</span>
          </template>
        </el-table-column>
        <el-table-column label="置顶" width="70" align="center">
          <template slot-scope="{ row }">
            <i :class="[row.is_top === 1 ? 'el-icon-star-on' : 'el-icon-star-off', { 'star-active': row.is_top === 1 }]" class="icon-star" @click="handleToggleTop(row)" />
          </template>
        </el-table-column>
        <el-table-column label="推荐" width="70" align="center">
          <template slot-scope="{ row }">
            <i :class="[row.is_recommend === 1 ? 'el-icon-sunny' : 'el-icon-sunrise-1', { 'recommend-active': row.is_recommend === 1 }]" class="icon-recommend" @click="handleToggleRecommend(row)" />
          </template>
        </el-table-column>
        <el-table-column prop="published_at" label="发布时间" width="170" />
        <el-table-column label="操作" width="260" align="center" fixed="right">
          <template slot-scope="{ row }">
            <el-button type="text" size="small" icon="el-icon-edit" @click="handleEdit(row)">编辑</el-button>
            <el-button type="text" size="small" :icon="row.status === 'published' ? 'el-icon-download' : 'el-icon-upload2'" @click="handleToggleStatus(row)">
              {{ row.status === 'published' ? '下架' : '发布' }}
            </el-button>
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
    <el-dialog :title="dialogTitle" :visible.sync="dialogVisible" width="800px" :close-on-click-modal="false" top="5vh">
      <el-form ref="form" :model="form" :rules="formRules" label-width="100px" size="small">
        <el-row :gutter="20">
          <el-col :span="14">
            <el-form-item label="标题" prop="title">
              <el-input v-model="form.title" placeholder="请输入文章标题" />
            </el-form-item>
          </el-col>
          <el-col :span="10">
            <el-form-item label="作者" prop="author">
              <el-input v-model="form.author" placeholder="请输入作者名" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="10">
            <el-form-item label="分类" prop="category_id">
              <el-cascader
                v-model="form.category_id"
                :options="categoryOptions"
                :props="{ checkStrictly: true, value: 'id', label: 'name', children: 'children', emitPath: false }"
                placeholder="请选择分类"
                clearable
                filterable
                style="width: 100%"
              />
            </el-form-item>
          </el-col>
          <el-col :span="14">
            <el-form-item label="来源" prop="source">
              <el-input v-model="form.source" placeholder="请输入文章来源" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item label="摘要" prop="summary">
          <el-input v-model="form.summary" type="textarea" :rows="2" placeholder="请输入文章摘要" />
        </el-form-item>
        <el-form-item label="封面图">
          <el-upload
            class="cover-uploader"
            action=""
            :http-request="handleUploadCover"
            :show-file-list="false"
            accept="image/*"
          >
            <img v-if="form.cover_image" :src="form.cover_image" class="cover-preview" />
            <div v-else class="upload-placeholder">
              <i class="el-icon-plus" />
              <span>上传封面</span>
            </div>
          </el-upload>
        </el-form-item>
        <el-form-item label="内容" prop="content">
          <el-input
            v-model="form.content"
            type="textarea"
            :rows="12"
            placeholder="支持 Markdown 格式编写文章内容..."
            class="article-editor"
          />
        </el-form-item>
        <el-row :gutter="20">
          <el-col :span="8">
            <el-form-item label="状态" prop="status">
              <el-radio-group v-model="form.status">
                <el-radio label="draft">草稿</el-radio>
                <el-radio label="published">发布</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item label="是否置顶">
              <el-switch v-model="form.is_top" :active-value="1" :inactive-value="0" active-color="#FAB005" inactive-color="#E2E2EA" />
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item label="是否推荐">
              <el-switch v-model="form.is_recommend" :active-value="1" :inactive-value="0" active-color="#FA5252" inactive-color="#E2E2EA" />
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" size="small" :loading="submitLoading" @click="handleSubmit">确定</el-button>
      </div>
    </el-dialog>

    <!-- Detail Dialog -->
    <el-dialog title="文章详情" :visible.sync="detailVisible" width="800px" top="5vh">
      <div class="detail-container">
        <h2 class="detail-title">{{ detailData.title }}</h2>
        <div class="detail-meta">
          <span><i class="el-icon-user" /> {{ detailData.author }}</span>
          <span><i class="el-icon-folder-opened" /> {{ detailData.category_name }}</span>
          <span><i class="el-icon-view" /> 阅读 {{ detailData.view_count || 0 }}</span>
          <span><i class="el-icon-time" /> {{ detailData.published_at }}</span>
        </div>
        <el-divider />
        <div v-if="detailData.cover_image" class="detail-cover">
          <el-image :src="detailData.cover_image" fit="contain" style="max-width: 100%; max-height: 300px; border-radius: 8px" />
        </div>
        <div v-if="detailData.summary" class="detail-summary">{{ detailData.summary }}</div>
        <div class="detail-content" v-html="renderContent(detailData.content)" />
      </div>
      <div slot="footer">
        <el-button size="small" @click="detailVisible = false">关闭</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getArticleList, createArticle, updateArticle, deleteArticle, toggleArticleStatus, toggleArticleTop, toggleArticleRecommend, getCategoryTree } from '@/api/article'
import Pagination from '@/components/Pagination/index.vue'

export default {
  name: 'ArticleManagement',
  components: { Pagination },
  data() {
    return {
      loading: false,
      submitLoading: false,
      tableData: [],
      total: 0,
      categoryOptions: [],
      searchForm: {
        title: '',
        category_id: '',
        status: '',
        dateRange: [],
        page: 1,
        page_size: 20
      },
      dialogVisible: false,
      isEdit: false,
      form: {
        id: undefined,
        title: '',
        summary: '',
        author: '',
        source: '',
        category_id: '',
        cover_image: '',
        content: '',
        status: 'draft',
        is_top: 0,
        is_recommend: 0
      },
      formRules: {
        title: [{ required: true, message: '请输入文章标题', trigger: 'blur' }],
        author: [{ required: true, message: '请输入作者名', trigger: 'blur' }],
        content: [{ required: true, message: '请输入文章内容', trigger: 'blur' }]
      },
      detailVisible: false,
      detailData: {}
    }
  },
  computed: {
    dialogTitle() {
      return this.isEdit ? '编辑文章' : '新增文章'
    }
  },
  created() {
    this.loadData()
    this.loadCategories()
  },
  methods: {
    loadData() {
      this.loading = true
      const params = {
        page: this.searchForm.page,
        page_size: this.searchForm.page_size,
        title: this.searchForm.title || undefined,
        category_id: this.searchForm.category_id || undefined,
        status: this.searchForm.status || undefined,
        start_date: this.searchForm.dateRange && this.searchForm.dateRange[0] || undefined,
        end_date: this.searchForm.dateRange && this.searchForm.dateRange[1] || undefined
      }
      getArticleList(params).then(res => {
        this.tableData = res.data.list || []
        this.total = res.data.total || 0
      }).catch(() => {
        this.tableData = []
        this.total = 0
      }).finally(() => {
        this.loading = false
      })
    },
    loadCategories() {
      getCategoryTree().then(res => {
        this.categoryOptions = res.data || []
      }).catch(() => {})
    },
    getStatusType(status) {
      const map = { draft: 'info', published: 'success', unpublished: 'danger' }
      return map[status] || 'info'
    },
    getStatusLabel(status) {
      const map = { draft: '草稿', published: '已发布', unpublished: '已下架' }
      return map[status] || status
    },
    renderContent(content) {
      if (!content) return '<p style="color:#999">暂无内容</p>'
      return content.replace(/\n/g, '<br/>')
    },
    handleUploadCover(options) {
      const file = options.file
      const reader = new FileReader()
      reader.onload = (e) => {
        this.form.cover_image = e.target.result
      }
      reader.readAsDataURL(file)
    },
    handleSearch() {
      this.searchForm.page = 1
      this.loadData()
    },
    resetSearch() {
      this.searchForm = {
        title: '',
        category_id: '',
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
        title: '',
        summary: '',
        author: '',
        source: '',
        category_id: '',
        cover_image: '',
        content: '',
        status: 'draft',
        is_top: 0,
        is_recommend: 0
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
        const action = this.isEdit ? updateArticle : createArticle
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
    handleToggleStatus(row) {
      const newStatus = row.status === 'published' ? 'unpublished' : 'published'
      toggleArticleStatus(row.id, newStatus).then(() => {
        this.$message.success(newStatus === 'published' ? '发布成功' : '已下架')
        row.status = newStatus
      }).catch(() => {})
    },
    handleToggleTop(row) {
      toggleArticleTop(row.id).then(() => {
        row.is_top = row.is_top === 1 ? 0 : 1
        this.$message.success(row.is_top === 1 ? '已置顶' : '已取消置顶')
      }).catch(() => {})
    },
    handleToggleRecommend(row) {
      toggleArticleRecommend(row.id).then(() => {
        row.is_recommend = row.is_recommend === 1 ? 0 : 1
        this.$message.success(row.is_recommend === 1 ? '已推荐' : '已取消推荐')
      }).catch(() => {})
    },
    handleViewDetail(row) {
      this.detailData = { ...row }
      this.detailVisible = true
    },
    handleDelete(row) {
      this.$confirm('确定删除文章"' + row.title + '"吗？此操作不可恢复。', '确认删除', {
        confirmButtonText: '删除',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteArticle(row.id).then(() => {
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

.no-image {
  font-size: 12px;
  color: $text-secondary;
}

.view-count {
  font-weight: 500;
  color: $text-regular;
}

.icon-star {
  font-size: 18px;
  cursor: pointer;
  color: $border-base;
  transition: color $transition-duration;

  &:hover {
    color: $warning-color;
  }
}

.star-active {
  color: $warning-color !important;
}

.icon-recommend {
  font-size: 18px;
  cursor: pointer;
  color: $border-base;
  transition: color $transition-duration;

  &:hover {
    color: $danger-color;
  }
}

.recommend-active {
  color: $danger-color !important;
}

.cover-uploader {
  ::v-deep .el-upload {
    border: 1px dashed $border-base;
    border-radius: 8px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 160px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;

    &:hover {
      border-color: $primary-color;
    }
  }
}

.cover-preview {
  width: 160px;
  height: 100px;
  object-fit: cover;
  border-radius: 8px;
}

.upload-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: $text-secondary;

  i {
    font-size: 24px;
    margin-bottom: 4px;
  }

  span {
    font-size: 12px;
  }
}

.article-editor {
  ::v-deep textarea {
    font-family: 'Monaco', 'Menlo', 'Consolas', monospace;
    line-height: 1.6;
  }
}

.detail-container {
  padding: 0 10px;
}

.detail-title {
  font-size: 22px;
  font-weight: 700;
  color: $text-primary;
  margin: 0 0 16px;
  line-height: 1.4;
}

.detail-meta {
  display: flex;
  gap: 20px;
  font-size: 13px;
  color: $text-secondary;
  flex-wrap: wrap;

  i {
    margin-right: 4px;
  }
}

.detail-cover {
  text-align: center;
  margin-bottom: 20px;
}

.detail-summary {
  background: $bg-page;
  padding: 16px;
  border-radius: 8px;
  font-size: 14px;
  color: $text-regular;
  line-height: 1.6;
  margin-bottom: 20px;
  border-left: 3px solid $primary-color;
}

.detail-content {
  font-size: 15px;
  color: $text-regular;
  line-height: 1.8;
}
</style>
