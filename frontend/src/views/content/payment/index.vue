<template>
  <div class="page-container">
    <div class="content-card">
      <div class="page-header">
        <h2 class="page-title">支付配置</h2>
      </div>

      <el-tabs v-model="activeTab" class="payment-tabs">
        <!-- 微信支付 -->
        <el-tab-pane label="微信支付" name="wechat">
          <el-form ref="wechatForm" :model="wechatForm" :rules="wechatRules" label-width="140px" size="small" class="config-form">
            <el-form-item label="商户号" prop="mch_id">
              <el-input v-model="wechatForm.mch_id" placeholder="请输入微信支付商户号" />
            </el-form-item>
            <el-form-item label="应用ID" prop="app_id">
              <el-input v-model="wechatForm.app_id" placeholder="请输入微信公众号/小程序AppID" />
            </el-form-item>
            <el-form-item label="API密钥" prop="api_key">
              <el-input v-model="wechatForm.api_key" type="password" placeholder="请输入API密钥" show-password />
            </el-form-item>
            <el-form-item label="证书路径" prop="cert_path">
              <el-input v-model="wechatForm.cert_path" placeholder="例如 /cert/apiclient_cert.pem" />
            </el-form-item>
            <el-form-item label="密钥路径" prop="key_path">
              <el-input v-model="wechatForm.key_path" placeholder="例如 /cert/apiclient_key.pem" />
            </el-form-item>
            <el-form-item label="回调地址" prop="notify_url">
              <el-input v-model="wechatForm.notify_url" placeholder="请输入支付结果回调地址" />
            </el-form-item>
            <el-form-item label="沙箱模式">
              <el-switch v-model="wechatForm.sandbox_mode" active-color="#4C6EF5" inactive-color="#E2E2EA" />
              <span class="switch-tip">开启后将使用沙箱环境进行测试</span>
            </el-form-item>
            <el-form-item label="启用状态">
              <el-switch v-model="wechatForm.status" :active-value="1" :inactive-value="0" active-color="#40C057" inactive-color="#E2E2EA" />
            </el-form-item>
            <el-form-item label="备注">
              <el-input v-model="wechatForm.remark" type="textarea" :rows="3" placeholder="请输入备注信息" />
            </el-form-item>
            <el-form-item>
              <el-button type="primary" icon="el-icon-connection" :loading="testLoading" @click="handleTestWechat">测试连接</el-button>
              <el-button type="success" icon="el-icon-check" :loading="saveLoading" @click="handleSaveWechat">保存配置</el-button>
            </el-form-item>
          </el-form>
        </el-tab-pane>

        <!-- 支付宝 -->
        <el-tab-pane label="支付宝" name="alipay">
          <el-form ref="alipayForm" :model="alipayForm" :rules="alipayRules" label-width="140px" size="small" class="config-form">
            <el-divider content-position="left">基本信息</el-divider>
            <el-form-item label="应用ID (AppID)" prop="app_id">
              <el-input v-model="alipayForm.app_id" placeholder="请输入支付宝开放平台应用ID" />
              <div class="field-desc">在支付宝开放平台 -> 应用开发 -> 应用信息中获取</div>
            </el-form-item>
            <el-form-item label="签名方式" prop="sign_type">
              <el-radio-group v-model="alipayForm.sign_type">
                <el-radio label="RSA2">RSA2（推荐）</el-radio>
                <el-radio label="RSA">RSA</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item label="字符编码" prop="charset">
              <el-select v-model="alipayForm.charset" style="width: 100%">
                <el-option label="UTF-8" value="utf-8" />
                <el-option label="GBK" value="gbk" />
                <el-option label="GB2312" value="gb2312" />
              </el-select>
            </el-form-item>

            <el-divider content-position="left">密钥配置</el-divider>
            <el-form-item label="应用私钥" prop="private_key">
              <el-input v-model="alipayForm.private_key" type="textarea" :rows="4" placeholder="请输入应用私钥（PKCS8格式）" show-word-limit />
              <div class="field-desc">用于请求签名，请在支付宝开放平台生成并妥善保管</div>
            </el-form-item>
            <el-form-item label="支付宝公钥" prop="public_key">
              <el-input v-model="alipayForm.public_key" type="textarea" :rows="4" placeholder="请输入支付宝公钥" show-word-limit />
              <div class="field-desc">用于验证支付宝返回数据的签名，非应用公钥</div>
            </el-form-item>

            <el-divider content-position="left">回调地址</el-divider>
            <el-form-item label="异步通知地址" prop="notify_url">
              <el-input v-model="alipayForm.notify_url" placeholder="支付完成后支付宝异步通知的地址" />
              <div class="field-desc">必须为可直接访问的外网地址，不能包含参数</div>
            </el-form-item>
            <el-form-item label="同步跳转地址" prop="return_url">
              <el-input v-model="alipayForm.return_url" placeholder="支付完成后用户跳转的页面地址" />
            </el-form-item>

            <el-divider content-position="left">其他设置</el-divider>
            <el-form-item label="沙箱模式">
              <el-switch v-model="alipayForm.sandbox" active-color="#4C6EF5" inactive-color="#E2E2EA" />
              <span class="switch-tip">开启后将使用支付宝沙箱环境进行调试</span>
            </el-form-item>
            <el-form-item label="启用状态">
              <el-switch v-model="alipayForm.status" :active-value="1" :inactive-value="0" active-color="#40C057" inactive-color="#E2E2EA" />
            </el-form-item>
            <el-form-item label="备注">
              <el-input v-model="alipayForm.remark" type="textarea" :rows="3" placeholder="请输入备注信息" />
            </el-form-item>
            <el-form-item>
              <el-button type="primary" icon="el-icon-connection" :loading="alipayTestLoading" @click="handleTestAlipay">测试连接</el-button>
              <el-button type="success" icon="el-icon-check" :loading="alipaySaveLoading" @click="handleSaveAlipay">保存配置</el-button>
            </el-form-item>
          </el-form>
        </el-tab-pane>
      </el-tabs>
    </div>
  </div>
</template>

<script>
import { getPaymentConfig, savePaymentConfig, testPayment, getAlipayConfig, saveAlipayConfig, testAlipay } from '@/api/payment'

export default {
  name: 'PaymentConfig',
  data() {
    return {
      activeTab: 'wechat',
      // 微信支付
      saveLoading: false,
      testLoading: false,
      wechatForm: {
        id: undefined,
        mch_id: '',
        app_id: '',
        api_key: '',
        cert_path: '',
        key_path: '',
        notify_url: '',
        sandbox_mode: false,
        status: 1,
        remark: ''
      },
      wechatRules: {
        mch_id: [{ required: true, message: '请输入商户号', trigger: 'blur' }],
        app_id: [{ required: true, message: '请输入应用ID', trigger: 'blur' }],
        api_key: [{ required: true, message: '请输入API密钥', trigger: 'blur' }],
        notify_url: [{ required: true, message: '请输入回调地址', trigger: 'blur' }]
      },
      // 支付宝
      alipaySaveLoading: false,
      alipayTestLoading: false,
      alipayForm: {
        id: undefined,
        app_id: '',
        private_key: '',
        public_key: '',
        notify_url: '/api/payment/alipay/notify',
        return_url: '/api/payment/alipay/return',
        sign_type: 'RSA2',
        charset: 'utf-8',
        format: 'json',
        sandbox: 0,
        status: 0,
        remark: ''
      },
      alipayRules: {
        app_id: [{ required: true, message: '请输入支付宝应用ID', trigger: 'blur' }],
        private_key: [{ required: true, message: '请输入应用私钥', trigger: 'blur' }],
        public_key: [{ required: true, message: '请输入支付宝公钥', trigger: 'blur' }],
        notify_url: [{ required: true, message: '请输入异步通知地址', trigger: 'blur' }]
      }
    }
  },
  created() {
    this.loadWechatConfig()
    this.loadAlipayConfig()
  },
  methods: {
    loadWechatConfig() {
      getPaymentConfig().then(res => {
        const list = res.data || []
        const config = Array.isArray(list) ? (list.find(item => item.channel === 'wechat') || {}) : list
        Object.keys(this.wechatForm).forEach(key => {
          if (config[key] !== undefined && config[key] !== null) {
            this.wechatForm[key] = config[key]
          }
        })
      }).catch(() => {})
    },
    handleTestWechat() {
      this.$refs.wechatForm.validate(valid => {
        if (!valid) return
        this.testLoading = true
        testPayment(this.wechatForm).then(() => {
          this.$message.success('连接测试成功，微信支付配置正常')
        }).catch(() => {}).finally(() => {
          this.testLoading = false
        })
      })
    },
    handleSaveWechat() {
      this.$refs.wechatForm.validate(valid => {
        if (!valid) return
        this.saveLoading = true
        savePaymentConfig(this.wechatForm).then(() => {
          this.$message.success('微信支付配置保存成功')
        }).catch(() => {}).finally(() => {
          this.saveLoading = false
        })
      })
    },
    loadAlipayConfig() {
      getAlipayConfig().then(res => {
        const data = res.data || {}
        Object.keys(this.alipayForm).forEach(key => {
          if (data[key] !== undefined && data[key] !== null) {
            this.alipayForm[key] = data[key]
          }
        })
      }).catch(() => {})
    },
    handleTestAlipay() {
      this.$refs.alipayForm.validate(valid => {
        if (!valid) return
        this.alipayTestLoading = true
        testAlipay().then(() => {
          this.$message.success('连接测试成功，支付宝配置正常')
        }).catch(() => {}).finally(() => {
          this.alipayTestLoading = false
        })
      })
    },
    handleSaveAlipay() {
      this.$refs.alipayForm.validate(valid => {
        if (!valid) return
        this.alipaySaveLoading = true
        saveAlipayConfig(this.alipayForm).then(() => {
          this.$message.success('支付宝配置保存成功')
        }).catch(() => {}).finally(() => {
          this.alipaySaveLoading = false
        })
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/styles/variables.scss';

.page-header {
  margin-bottom: 20px;
}

.page-title {
  font-size: 20px;
  font-weight: 700;
  color: $text-primary;
  margin: 0;
}

.payment-tabs {
  ::v-deep .el-tabs__header {
    margin-bottom: 24px;
  }

  ::v-deep .el-tabs__item {
    font-size: 15px;
    font-weight: 500;

    &.is-active {
      color: $primary-color;
    }
  }

  ::v-deep .el-tabs__active-bar {
    background-color: $primary-color;
  }
}

.config-form {
  max-width: 680px;
}

.switch-tip {
  margin-left: 10px;
  font-size: 12px;
  color: $text-secondary;
}

.field-desc {
  font-size: 12px;
  color: $text-secondary;
  line-height: 1.4;
  margin-top: 4px;
}

::v-deep .el-divider__text {
  font-size: 14px;
  font-weight: 600;
  color: $text-primary;
  background-color: $bg-page;
}

::v-deep .el-divider {
  background-color: $border-light;
}
</style>
