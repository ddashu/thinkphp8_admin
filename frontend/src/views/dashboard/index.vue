<template>
  <div class="dashboard-container">
    <!-- Stats Cards -->
    <el-row :gutter="16" class="stat-cards">
      <el-col :xs="12" :sm="12" :md="6">
        <div class="stat-card">
          <div class="stat-icon" style="background: linear-gradient(135deg, #4C6EF5, #748FFC);">
            <i class="el-icon-user"></i>
          </div>
          <div class="stat-info">
            <div class="stat-value">{{ stats.user_count }}</div>
            <div class="stat-label">用户总数</div>
          </div>
          <div class="stat-trend up">
            <i class="el-icon-top"></i> 12.5%
          </div>
        </div>
      </el-col>
      <el-col :xs="12" :sm="12" :md="6">
        <div class="stat-card">
          <div class="stat-icon" style="background: linear-gradient(135deg, #15AABF, #22D3EE);">
            <i class="el-icon-connection"></i>
          </div>
          <div class="stat-info">
            <div class="stat-value">{{ stats.api_key_count }}</div>
            <div class="stat-label">API调用量</div>
          </div>
          <div class="stat-trend up">
            <i class="el-icon-top"></i> 8.3%
          </div>
        </div>
      </el-col>
      <el-col :xs="12" :sm="12" :md="6">
        <div class="stat-card">
          <div class="stat-icon" style="background: linear-gradient(135deg, #40C057, #69DB7C);">
            <i class="el-icon-cpu"></i>
          </div>
          <div class="stat-info">
            <div class="stat-value">{{ stats.model_count }}</div>
            <div class="stat-label">AI模型数</div>
          </div>
          <div class="stat-trend up">
            <i class="el-icon-top"></i> 3.2%
          </div>
        </div>
      </el-col>
      <el-col :xs="12" :sm="12" :md="6">
        <div class="stat-card">
          <div class="stat-icon" style="background: linear-gradient(135deg, #FAB005, #FFD43B);">
            <i class="el-icon-coin"></i>
          </div>
          <div class="stat-info">
            <div class="stat-value">{{ stats.today_log_count }}</div>
            <div class="stat-label">今日收入</div>
          </div>
          <div class="stat-trend down">
            <i class="el-icon-bottom"></i> 2.1%
          </div>
        </div>
      </el-col>
    </el-row>

    <!-- Charts Row 1 -->
    <el-row :gutter="16" class="chart-row">
      <el-col :xs="24" :md="12">
        <div class="chart-card">
          <div class="chart-header">
            <h3 class="chart-title">用户增长趋势</h3>
            <el-radio-group v-model="userTrendRange" size="mini">
              <el-radio-button label="week">近7天</el-radio-button>
              <el-radio-button label="month">近30天</el-radio-button>
              <el-radio-button label="year">全年</el-radio-button>
            </el-radio-group>
          </div>
          <div ref="userTrendChart" class="chart-body"></div>
        </div>
      </el-col>
      <el-col :xs="24" :md="12">
        <div class="chart-card">
          <div class="chart-header">
            <h3 class="chart-title">API调用统计</h3>
            <el-radio-group v-model="apiStatsRange" size="mini">
              <el-radio-button label="week">近7天</el-radio-button>
              <el-radio-button label="month">近30天</el-radio-button>
            </el-radio-group>
          </div>
          <div ref="apiStatsChart" class="chart-body"></div>
        </div>
      </el-col>
    </el-row>

    <!-- Charts Row 2 -->
    <el-row :gutter="16" class="chart-row">
      <el-col :xs="24" :md="8">
        <div class="chart-card">
          <div class="chart-header">
            <h3 class="chart-title">模型使用分布</h3>
          </div>
          <div ref="modelPieChart" class="chart-body"></div>
        </div>
      </el-col>
      <el-col :xs="24" :md="16">
        <div class="chart-card">
          <div class="chart-header">
            <h3 class="chart-title">最近操作</h3>
          </div>
          <div class="operation-list">
            <div v-for="item in recentOps" :key="item.id" class="operation-item">
              <div class="op-icon" :class="item.type">
                <i :class="item.icon"></i>
              </div>
              <div class="op-content">
                <div class="op-desc">{{ item.description }}</div>
                <div class="op-meta">
                  <span class="op-user">{{ item.username }}</span>
                  <span class="op-time">{{ item.time }}</span>
                </div>
              </div>
            </div>
            <div v-if="recentOps.length === 0" class="empty-tip">暂无最近操作</div>
          </div>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import * as echarts from 'echarts'
import { getDashboardStats, getUserTrend, getApiStats, getModelDistribution } from '@/api/dashboard'

export default {
  name: 'Dashboard',
  data() {
    return {
      stats: {
        user_count: 0,
        api_key_count: 0,
        model_count: 0,
        today_log_count: 0
      },
      userTrendRange: 'week',
      apiStatsRange: 'week',
      recentOps: [],
      charts: {}
    }
  },
  watch: {
    userTrendRange() {
      this.loadUserTrend()
    },
    apiStatsRange() {
      this.loadApiStats()
    }
  },
  mounted() {
    this.loadStats()
    this.loadUserTrend()
    this.loadApiStats()
    this.loadModelDistribution()
    this.recentOps = [
      { id: 1, type: 'success', icon: 'el-icon-plus', description: '创建了API密钥"生产密钥"', username: 'admin', time: '5分钟前' },
      { id: 2, type: 'warning', icon: 'el-icon-edit', description: '更新了模型"GPT-4"配置', username: 'admin', time: '12分钟前' },
      { id: 3, type: 'danger', icon: 'el-icon-delete', description: '删除了用户"test_user"', username: 'admin', time: '1小时前' },
      { id: 4, type: 'info', icon: 'el-icon-setting', description: '更新了系统配置', username: 'admin', time: '2小时前' },
      { id: 5, type: 'success', icon: 'el-icon-check', description: '启用了模型"Claude-3"', username: 'admin', time: '3小时前' }
    ]
    window.addEventListener('resize', this.handleResize)
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.handleResize)
    Object.values(this.charts).forEach(chart => {
      if (chart) chart.dispose()
    })
  },
  methods: {
    handleResize() {
      Object.values(this.charts).forEach(chart => {
        if (chart) chart.resize()
      })
    },
    loadStats() {
      getDashboardStats().then(res => {
        if (res.data) {
          this.stats = res.data
        }
      }).catch(() => {})
    },
    loadUserTrend() {
      getUserTrend({ range: this.userTrendRange }).then(res => {
        this.renderUserTrend(res.data)
      }).catch(() => {
        this.renderUserTrend(this.getMockUserTrend())
      })
    },
    loadApiStats() {
      getApiStats({ range: this.apiStatsRange }).then(res => {
        this.renderApiStats(res.data)
      }).catch(() => {
        this.renderApiStats(this.getMockApiStats())
      })
    },
    loadModelDistribution() {
      getModelDistribution().then(res => {
        this.renderModelPie(res.data)
      }).catch(() => {
        this.renderModelPie(this.getMockModelPie())
      })
    },
    getMockUserTrend() {
      return {
        dates: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
        newUsers: [120, 132, 101, 134, 90, 230, 210],
        activeUsers: [220, 182, 191, 234, 290, 330, 310]
      }
    },
    getMockApiStats() {
      return {
        dates: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
        success: [820, 932, 901, 934, 1290, 1330, 1320],
        errors: [20, 32, 11, 34, 30, 50, 40]
      }
    },
    getMockModelPie() {
      return [
        { name: 'GPT-4', value: 35 },
        { name: 'Claude-3', value: 25 },
        { name: 'Gemini Pro', value: 20 },
        { name: 'Llama 3', value: 12 },
        { name: '其他', value: 8 }
      ]
    },
    renderUserTrend(data) {
      if (!this.$refs.userTrendChart) return
      if (!this.charts.userTrend) {
        this.charts.userTrend = echarts.init(this.$refs.userTrendChart)
      }
      const option = {
        tooltip: {
          trigger: 'axis',
          backgroundColor: '#fff',
          borderColor: '#E2E2EA',
          borderWidth: 1,
          textStyle: { color: '#1A1A2E', fontSize: 13 },
          axisPointer: { type: 'cross', crossStyle: { color: '#999' } }
        },
        legend: {
          data: ['新增用户', '活跃用户'],
          bottom: 0,
          textStyle: { color: '#8E8EA0', fontSize: 12 },
          itemWidth: 12,
          itemHeight: 12,
          itemGap: 20
        },
        grid: { top: 20, right: 20, bottom: 40, left: 50 },
        xAxis: {
          type: 'category',
          data: data.dates,
          axisLine: { lineStyle: { color: '#E2E2EA' } },
          axisTick: { show: false },
          axisLabel: { color: '#8E8EA0', fontSize: 12 }
        },
        yAxis: {
          type: 'value',
          axisLine: { show: false },
          axisTick: { show: false },
          splitLine: { lineStyle: { color: '#F0F0F5', type: 'dashed' } },
          axisLabel: { color: '#8E8EA0', fontSize: 12 }
        },
        series: [
          {
            name: '新增用户',
            type: 'line',
            smooth: true,
            symbol: 'circle',
            symbolSize: 6,
            lineStyle: { width: 2, color: '#4C6EF5' },
            itemStyle: { color: '#4C6EF5' },
            areaStyle: {
              color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                { offset: 0, color: 'rgba(76, 110, 245, 0.25)' },
                { offset: 1, color: 'rgba(76, 110, 245, 0.02)' }
              ])
            },
            data: data.newUsers
          },
          {
            name: '活跃用户',
            type: 'line',
            smooth: true,
            symbol: 'circle',
            symbolSize: 6,
            lineStyle: { width: 2, color: '#15AABF' },
            itemStyle: { color: '#15AABF' },
            areaStyle: {
              color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                { offset: 0, color: 'rgba(21, 170, 191, 0.15)' },
                { offset: 1, color: 'rgba(21, 170, 191, 0.02)' }
              ])
            },
            data: data.activeUsers
          }
        ]
      }
      this.charts.userTrend.setOption(option)
    },
    renderApiStats(data) {
      if (!this.$refs.apiStatsChart) return
      if (!this.charts.apiStats) {
        this.charts.apiStats = echarts.init(this.$refs.apiStatsChart)
      }
      const option = {
        tooltip: {
          trigger: 'axis',
          backgroundColor: '#fff',
          borderColor: '#E2E2EA',
          borderWidth: 1,
          textStyle: { color: '#1A1A2E', fontSize: 13 }
        },
        legend: {
          data: ['成功', '失败'],
          bottom: 0,
          textStyle: { color: '#8E8EA0', fontSize: 12 },
          itemWidth: 12,
          itemHeight: 12,
          itemGap: 20
        },
        grid: { top: 20, right: 20, bottom: 40, left: 50 },
        xAxis: {
          type: 'category',
          data: data.dates,
          axisLine: { lineStyle: { color: '#E2E2EA' } },
          axisTick: { show: false },
          axisLabel: { color: '#8E8EA0', fontSize: 12 }
        },
        yAxis: {
          type: 'value',
          axisLine: { show: false },
          axisTick: { show: false },
          splitLine: { lineStyle: { color: '#F0F0F5', type: 'dashed' } },
          axisLabel: { color: '#8E8EA0', fontSize: 12 }
        },
        series: [
          {
            name: '成功',
            type: 'bar',
            barWidth: 20,
            barGap: '30%',
            itemStyle: {
              color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                { offset: 0, color: '#4C6EF5' },
                { offset: 1, color: '#748FFC' }
              ]),
              borderRadius: [4, 4, 0, 0]
            },
            data: data.success
          },
          {
            name: '失败',
            type: 'bar',
            barWidth: 20,
            itemStyle: {
              color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                { offset: 0, color: '#FA5252' },
                { offset: 1, color: '#FF6B6B' }
              ]),
              borderRadius: [4, 4, 0, 0]
            },
            data: data.errors
          }
        ]
      }
      this.charts.apiStats.setOption(option)
    },
    renderModelPie(data) {
      if (!this.$refs.modelPieChart) return
      if (!this.charts.modelPie) {
        this.charts.modelPie = echarts.init(this.$refs.modelPieChart)
      }
      const option = {
        tooltip: {
          trigger: 'item',
          backgroundColor: '#fff',
          borderColor: '#E2E2EA',
          borderWidth: 1,
          textStyle: { color: '#1A1A2E', fontSize: 13 },
          formatter: '{b}: {c} ({d}%)'
        },
        legend: {
          orient: 'vertical',
          right: 10,
          top: 'center',
          textStyle: { color: '#8E8EA0', fontSize: 12 },
          itemWidth: 10,
          itemHeight: 10,
          itemGap: 12
        },
        series: [
          {
            type: 'pie',
            radius: ['45%', '70%'],
            center: ['35%', '50%'],
            avoidLabelOverlap: false,
            label: { show: false },
            emphasis: {
              label: { show: true, fontSize: 14, fontWeight: 'bold' }
            },
            labelLine: { show: false },
            data: data.map((item, index) => {
              const colors = ['#4C6EF5', '#15AABF', '#40C057', '#FAB005', '#8E8EA0']
              return {
                ...item,
                itemStyle: { color: colors[index % colors.length] }
              }
            })
          }
        ]
      }
      this.charts.modelPie.setOption(option)
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/styles/variables.scss';

.dashboard-container {
  padding: 0;
}

// Stat Cards
.stat-cards {
  margin-bottom: 16px;
}

.stat-card {
  background: $bg-card;
  border-radius: $card-border-radius;
  padding: 20px;
  box-shadow: $card-shadow;
  display: flex;
  align-items: center;
  gap: 16px;
  transition: box-shadow 0.2s;

  &:hover {
    box-shadow: $card-shadow-hover;
  }
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;

  i {
    font-size: 22px;
    color: #fff;
  }
}

.stat-info {
  flex: 1;
  min-width: 0;
}

.stat-value {
  font-size: 22px;
  font-weight: 700;
  color: $text-primary;
  line-height: 1.2;
}

.stat-label {
  font-size: 13px;
  color: $text-secondary;
  margin-top: 4px;
}

.stat-trend {
  font-size: 12px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 2px;

  &.up { color: $success-color; }
  &.down { color: $danger-color; }
}

// Chart Cards
.chart-row {
  margin-bottom: 16px;
}

.chart-card {
  background: $bg-card;
  border-radius: $card-border-radius;
  padding: 20px;
  box-shadow: $card-shadow;
  height: 100%;
}

.chart-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
}

.chart-title {
  font-size: 16px;
  font-weight: 600;
  color: $text-primary;
}

.chart-body {
  height: 300px;
}

// Operation List
.operation-list {
  max-height: 300px;
  overflow-y: auto;
}

.operation-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 0;
  border-bottom: 1px solid $border-light;

  &:last-child {
    border-bottom: none;
  }
}

.op-icon {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;

  i {
    font-size: 16px;
    color: #fff;
  }

  &.success { background: $success-color; }
  &.warning { background: $warning-color; }
  &.danger { background: $danger-color; }
  &.info { background: $info-color; }
}

.op-content {
  flex: 1;
  min-width: 0;
}

.op-desc {
  font-size: 14px;
  color: $text-primary;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.op-meta {
  display: flex;
  gap: 12px;
  margin-top: 4px;
  font-size: 12px;
  color: $text-secondary;
}

.empty-tip {
  text-align: center;
  padding: 40px 0;
  color: $text-secondary;
  font-size: 14px;
}
</style>
