<template>
  <div class="page-container">
    <!-- Search Bar -->
    <div class="search-bar">
      <el-form :model="searchForm" inline size="small">
        <el-form-item label="用户名">
          <el-input v-model="searchForm.username" placeholder="搜索用户名" clearable style="width: 150px" />
        </el-form-item>
        <el-form-item label="模块">
          <el-select v-model="searchForm.module" placeholder="全部" clearable style="width: 140px">
            <el-option label="用户" value="user" />
            <el-option label="角色" value="role" />
            <el-option label="菜单" value="menu" />
            <el-option label="API密钥" value="apikey" />
            <el-option label="模型" value="model" />
            <el-option label="配置" value="config" />
            <el-option label="文件" value="file" />
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
        <h3 class="table-title">操作日志</h3>
      </div>

      <el-table v-loading="loading" :data="tableData" border stripe>
        <el-table-column prop="id" label="ID" width="70" align="center" />
        <el-table-column prop="username" label="用户名" width="120" />
        <el-table-column prop="module" label="模块" width="100" align="center">
          <template slot-scope="{ row }">
            <el-tag size="mini">{{ row.module }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="action" label="操作" width="100" align="center">
          <template slot-scope="{ row }">
            <el-tag size="mini" :type="actionTagType(row.action)">{{ row.action }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="description" label="描述" min-width="240" show-overflow-tooltip />
        <el-table-column prop="ip" label="IP地址" width="130" />
        <el-table-column prop="create_time" label="操作时间" width="170" />
        <el-table-column label="操作" width="100" align="center" fixed="right">
          <template slot-scope="{ row }">
            <el-button type="text" size="small" icon="el-icon-view" @click="handleDetail(row)">详情</el-button>
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

    <!-- Detail Dialog -->
    <el-dialog title="日志详情" :visible.sync="detailVisible" width="600px">
      <el-descriptions :column="2" border size="small" v-if="detailData">
        <el-descriptions-item label="用户名">{{ detailData.username }}</el-descriptions-item>
        <el-descriptions-item label="模块">{{ detailData.module }}</el-descriptions-item>
        <el-descriptions-item label="操作">{{ detailData.action }}</el-descriptions-item>
        <el-descriptions-item label="IP地址">{{ detailData.ip }}</el-descriptions-item>
        <el-descriptions-item label="请求方式">{{ detailData.method }}</el-descriptions-item>
        <el-descriptions-item label="操作时间" :span="2">{{ detailData.create_time }}</el-descriptions-item>
        <el-descriptions-item label="请求地址" :span="2">{{ detailData.url }}</el-descriptions-item>
        <el-descriptions-item label="请求数据" :span="2">
          <pre class="log-json">{{ formatJson(detailData.params) }}</pre>
        </el-descriptions-item>
        <el-descriptions-item label="用户代理" :span="2">
          <div style="word-break: break-all; font-size: 12px;">{{ detailData.user_agent || '-' }}</div>
        </el-descriptions-item>
      </el-descriptions>
    </el-dialog>
  </div>
</template>

<script>
import { getOperationLogList, getOperationLogDetail } from '@/api/log'
import Pagination from '@/components/Pagination/index.vue'

export default {
  name: 'OperationLog',
  components: { Pagination },
  data() {
    return {
      loading: false,
      tableData: [],
      total: 0,
      searchForm: {
        username: '',
        module: '',
        dateRange: [],
        page: 1,
        page_size: 20
      },
      detailVisible: false,
      detailData: null
    }
  },
  created() {
    this.loadData()
  },
  methods: {
    actionTagType(action) {
      const map = { create: 'success', update: 'warning', delete: 'danger', login: 'info' }
      return map[action] || ''
    },
    loadData() {
      this.loading = true
      const params = {
        page: this.searchForm.page,
        page_size: this.searchForm.page_size,
        username: this.searchForm.username || undefined,
        module: this.searchForm.module || undefined,
        start_date: this.searchForm.dateRange && this.searchForm.dateRange[0] || undefined,
        end_date: this.searchForm.dateRange && this.searchForm.dateRange[1] || undefined
      }
      getOperationLogList(params).then(res => {
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
      this.searchForm = { username: '', module: '', dateRange: [], page: 1, page_size: 20 }
      this.loadData()
    },
    handleDetail(row) {
      getOperationLogDetail(row.id).then(res => {
        this.detailData = res.data
        this.detailVisible = true
      }).catch(() => {
        this.detailData = row
        this.detailVisible = true
      })
    },
    formatJson(data) {
      if (!data) return '-'
      try {
        return typeof data === 'string' ? JSON.stringify(JSON.parse(data), null, 2) : JSON.stringify(data, null, 2)
      } catch (e) {
        return data
      }
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

.log-json {
  background: $bg-page;
  padding: 12px;
  border-radius: 8px;
  font-family: 'Courier New', monospace;
  font-size: 12px;
  max-height: 200px;
  overflow: auto;
  white-space: pre-wrap;
  word-break: break-all;
  margin: 0;
}
</style>
