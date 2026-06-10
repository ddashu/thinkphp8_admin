<template>
  <div class="page-container">
    <div class="content-card">
      <div class="table-toolbar">
        <h3 class="table-title">文件管理</h3>
        <div class="toolbar-actions">
          <el-radio-group v-model="viewMode" size="small">
            <el-radio-button label="grid">
              <i class="el-icon-s-grid"></i>
            </el-radio-button>
            <el-radio-button label="list">
              <i class="el-icon-s-unfold"></i>
            </el-radio-button>
          </el-radio-group>
          <el-button type="primary" size="small" icon="el-icon-upload2" @click="showUpload = true">上传</el-button>
        </div>
      </div>

      <!-- Upload Area -->
      <el-dialog title="上传文件" :visible.sync="showUpload" width="500px" :close-on-click-modal="false">
        <el-upload
          ref="upload"
          :action="uploadUrl"
          :headers="uploadHeaders"
          :on-success="handleUploadSuccess"
          :on-error="handleUploadError"
          :file-list="uploadFileList"
          multiple
          drag
        >
          <i class="el-icon-upload"></i>
          <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
          <div slot="tip" class="el-upload__tip">支持 jpg/png/gif/pdf/doc/docx 文件，最大10MB</div>
        </el-upload>
        <div slot="footer">
          <el-button size="small" @click="showUpload = false">关闭</el-button>
        </div>
      </el-dialog>

      <!-- Grid View -->
      <div v-if="viewMode === 'grid'" class="file-grid">
        <div v-for="file in tableData" :key="file.id" class="file-card">
          <div class="file-preview" @click="previewFile(file)">
            <img v-if="isImage(file.mime_type)" :src="file.url" alt="" />
            <div v-else class="file-icon">
              <i :class="getFileIcon(file.mime_type)"></i>
            </div>
          </div>
          <div class="file-info">
            <div class="file-name" :title="file.name">{{ file.name }}</div>
            <div class="file-meta">{{ formatSize(file.size) }}</div>
            <div class="file-actions">
              <el-button type="text" size="mini" @click="copyUrl(file)">
                <i class="el-icon-document-copy"></i>
              </el-button>
              <el-button type="text" size="mini" style="color: #FA5252" @click="handleDelete(file)">
                <i class="el-icon-delete"></i>
              </el-button>
            </div>
          </div>
        </div>
        <div v-if="tableData.length === 0" class="empty-state">
          <i class="el-icon-folder-opened"></i>
          <p>暂无文件</p>
        </div>
      </div>

      <!-- List View -->
      <div v-if="viewMode === 'list'">
        <el-table v-loading="loading" :data="tableData" border stripe>
          <el-table-column type="selection" width="50" />
          <el-table-column label="文件" min-width="280">
            <template slot-scope="{ row }">
              <div class="file-cell">
                <i :class="getFileIcon(row.mime_type)" class="file-cell-icon"></i>
                <span class="file-cell-name">{{ row.name }}</span>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="mime_type" label="类型" width="140" />
          <el-table-column label="大小" width="100" align="center">
            <template slot-scope="{ row }">{{ formatSize(row.size) }}</template>
          </el-table-column>
          <el-table-column prop="create_time" label="上传时间" width="170" />
          <el-table-column label="操作" width="160" align="center">
            <template slot-scope="{ row }">
              <el-button type="text" size="small" @click="copyUrl(row)">复制链接</el-button>
              <el-button type="text" size="small" style="color: #FA5252" @click="handleDelete(row)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>

      <pagination
        :total="total"
        :page.sync="searchForm.page"
        :limit.sync="searchForm.page_size"
        @pagination="loadData"
      />
    </div>
  </div>
</template>

<script>
import { getFileList, deleteFile } from '@/api/file'
import { getToken } from '@/utils/auth'
import Pagination from '@/components/Pagination/index.vue'

export default {
  name: 'FileManagement',
  components: { Pagination },
  data() {
    return {
      loading: false,
      tableData: [],
      total: 0,
      viewMode: 'grid',
      showUpload: false,
      uploadFileList: [],
      searchForm: {
        page: 1,
        page_size: 20
      }
    }
  },
  computed: {
    uploadUrl() {
      return process.env.VUE_APP_BASE_API + '/file/upload'
    },
    uploadHeaders() {
      return {
        Authorization: 'Bearer ' + getToken()
      }
    }
  },
  created() {
    this.loadData()
  },
  methods: {
    loadData() {
      this.loading = true
      getFileList(this.searchForm).then(res => {
        this.tableData = res.data.list || []
        this.total = res.data.total || 0
      }).catch(() => {
        this.tableData = []
        this.total = 0
      }).finally(() => {
        this.loading = false
      })
    },
    isImage(mimeType) {
      return mimeType && mimeType.startsWith('image/')
    },
    getFileIcon(mimeType) {
      if (!mimeType) return 'el-icon-document'
      if (mimeType.startsWith('image/')) return 'el-icon-picture'
      if (mimeType.startsWith('video/')) return 'el-icon-video-camera'
      if (mimeType.startsWith('audio/')) return 'el-icon-headset'
      if (mimeType.includes('pdf')) return 'el-icon-document'
      if (mimeType.includes('word') || mimeType.includes('doc')) return 'el-icon-document'
      if (mimeType.includes('excel') || mimeType.includes('sheet')) return 'el-icon-s-grid'
      if (mimeType.includes('zip') || mimeType.includes('rar')) return 'el-icon-suitcase'
      return 'el-icon-document'
    },
    formatSize(bytes) {
      if (!bytes) return '0 B'
      const units = ['B', 'KB', 'MB', 'GB']
      let i = 0
      let size = bytes
      while (size >= 1024 && i < units.length - 1) {
        size /= 1024
        i++
      }
      return size.toFixed(i === 0 ? 0 : 1) + ' ' + units[i]
    },
    previewFile(file) {
      if (this.isImage(file.mime_type) && file.url) {
        window.open(file.url, '_blank')
      }
    },
    copyUrl(file) {
      if (!file.url) return
      const textarea = document.createElement('textarea')
      textarea.value = file.url
      document.body.appendChild(textarea)
      textarea.select()
      document.execCommand('copy')
      document.body.removeChild(textarea)
      this.$message.success('链接已复制到剪贴板')
    },
    handleUploadSuccess(response) {
      this.$message.success('文件上传成功')
      this.loadData()
    },
    handleUploadError() {
      this.$message.error('上传失败')
    },
    handleDelete(file) {
      this.$confirm('确定删除文件"' + file.name + '"吗？', '确认', {
        confirmButtonText: '删除', cancelButtonText: '取消', type: 'warning'
      }).then(() => {
        deleteFile(file.id).then(() => {
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

.toolbar-actions {
  display: flex;
  gap: 12px;
  align-items: center;
}

.file-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 16px;
}

.file-card {
  background: $bg-page;
  border-radius: 10px;
  overflow: hidden;
  transition: box-shadow 0.2s;
  border: 1px solid $border-light;

  &:hover {
    box-shadow: $card-shadow-hover;
  }
}

.file-preview {
  height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fff;
  cursor: pointer;
  overflow: hidden;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}

.file-icon {
  i {
    font-size: 40px;
    color: $text-secondary;
  }
}

.file-info {
  padding: 10px;
}

.file-name {
  font-size: 13px;
  color: $text-primary;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 4px;
}

.file-meta {
  font-size: 12px;
  color: $text-secondary;
  margin-bottom: 6px;
}

.file-actions {
  display: flex;
  gap: 4px;
}

.empty-state {
  grid-column: 1 / -1;
  text-align: center;
  padding: 60px 0;
  color: $text-secondary;

  i {
    font-size: 48px;
    margin-bottom: 12px;
    display: block;
  }

  p {
    font-size: 14px;
  }
}

.file-cell {
  display: flex;
  align-items: center;
  gap: 8px;
}

.file-cell-icon {
  font-size: 20px;
  color: $text-secondary;
}

.file-cell-name {
  font-size: 13px;
  color: $text-primary;
}
</style>
