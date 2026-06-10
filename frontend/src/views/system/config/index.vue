<template>
  <div class="page-container">
    <div class="content-card">
      <el-tabs v-model="activeTab" @tab-click="handleTabChange">
        <el-tab-pane label="基本配置" name="basic">
          <el-form ref="basicForm" :model="basicForm" label-width="160px" size="small" class="config-form">
            <el-form-item label="站点名称">
              <el-input v-model="basicForm.site_name" placeholder="请输入站点名称" />
            </el-form-item>
            <el-form-item label="站点描述">
              <el-input v-model="basicForm.site_description" type="textarea" :rows="3" placeholder="请输入站点描述" />
            </el-form-item>
            <el-form-item label="Logo地址">
              <el-input v-model="basicForm.logo_url" placeholder="请输入Logo地址" />
            </el-form-item>
            <el-form-item label="默认语言">
              <el-select v-model="basicForm.default_language" style="width: 200px">
                <el-option label="英文" value="en" />
                <el-option label="中文" value="zh" />
              </el-select>
            </el-form-item>
            <el-form-item label="开放注册">
              <el-switch v-model="basicForm.registration_enabled" active-color="#4C6EF5" />
            </el-form-item>
            <el-form-item label="维护模式">
              <el-switch v-model="basicForm.maintenance_mode" active-color="#FA5252" />
            </el-form-item>
            <el-form-item>
              <el-button type="primary" :loading="saveLoading" @click="handleSave('basic')">保存</el-button>
              <el-button @click="loadConfig('basic')">重置</el-button>
            </el-form-item>
          </el-form>
        </el-tab-pane>

        <el-tab-pane label="邮件配置" name="email">
          <el-form ref="emailForm" :model="emailForm" label-width="160px" size="small" class="config-form">
            <el-form-item label="SMTP主机">
              <el-input v-model="emailForm.smtp_host" placeholder="例如 smtp.gmail.com" />
            </el-form-item>
            <el-form-item label="SMTP端口">
              <el-input-number v-model="emailForm.smtp_port" :min="1" :max="65535" />
            </el-form-item>
            <el-form-item label="SMTP用户名">
              <el-input v-model="emailForm.smtp_username" placeholder="请输入SMTP用户名" />
            </el-form-item>
            <el-form-item label="SMTP密码">
              <el-input v-model="emailForm.smtp_password" type="password" placeholder="请输入SMTP密码" show-password />
            </el-form-item>
            <el-form-item label="加密方式">
              <el-radio-group v-model="emailForm.smtp_encryption">
                <el-radio label="none">无</el-radio>
                <el-radio label="tls">TLS</el-radio>
                <el-radio label="ssl">SSL</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item label="发件人邮箱">
              <el-input v-model="emailForm.from_email" placeholder="例如 noreply@example.com" />
            </el-form-item>
            <el-form-item label="发件人名称">
              <el-input v-model="emailForm.from_name" placeholder="请输入发件人名称" />
            </el-form-item>
            <el-form-item>
              <el-button type="primary" :loading="saveLoading" @click="handleSave('email')">保存</el-button>
              <el-button @click="loadConfig('email')">重置</el-button>
            </el-form-item>
          </el-form>
        </el-tab-pane>

        <el-tab-pane label="存储配置" name="storage">
          <el-form ref="storageForm" :model="storageForm" label-width="160px" size="small" class="config-form">
            <el-form-item label="存储驱动">
              <el-select v-model="storageForm.driver" style="width: 200px">
                <el-option label="本地" value="local" />
                <el-option label="S3" value="s3" />
                <el-option label="OSS" value="oss" />
              </el-select>
            </el-form-item>
            <el-form-item label="上传大小限制(MB)">
              <el-input-number v-model="storageForm.max_size" :min="1" :max="100" />
            </el-form-item>
            <el-form-item label="允许的扩展名">
              <el-input v-model="storageForm.allowed_extensions" placeholder="例如 jpg,png,gif,pdf,doc" />
            </el-form-item>
            <template v-if="storageForm.driver === 's3'">
              <el-form-item label="S3存储桶">
                <el-input v-model="storageForm.s3_bucket" placeholder="请输入S3存储桶" />
              </el-form-item>
              <el-form-item label="S3区域">
                <el-input v-model="storageForm.s3_region" placeholder="例如 us-east-1" />
              </el-form-item>
              <el-form-item label="S3 Access Key">
                <el-input v-model="storageForm.s3_access_key" placeholder="请输入Access Key" />
              </el-form-item>
              <el-form-item label="S3 Secret Key">
                <el-input v-model="storageForm.s3_secret_key" type="password" placeholder="请输入Secret Key" show-password />
              </el-form-item>
            </template>
            <template v-if="storageForm.driver === 'oss'">
              <el-form-item label="OSS存储桶">
                <el-input v-model="storageForm.oss_bucket" placeholder="请输入OSS存储桶" />
              </el-form-item>
              <el-form-item label="OSS节点">
                <el-input v-model="storageForm.oss_endpoint" placeholder="例如 oss-cn-hangzhou.aliyuncs.com" />
              </el-form-item>
              <el-form-item label="OSS Access Key ID">
                <el-input v-model="storageForm.oss_access_key_id" placeholder="请输入Access Key ID" />
              </el-form-item>
              <el-form-item label="OSS Access Key Secret">
                <el-input v-model="storageForm.oss_access_key_secret" type="password" placeholder="请输入Access Key Secret" show-password />
              </el-form-item>
            </template>
            <el-form-item>
              <el-button type="primary" :loading="saveLoading" @click="handleSave('storage')">保存</el-button>
              <el-button @click="loadConfig('storage')">重置</el-button>
            </el-form-item>
          </el-form>
        </el-tab-pane>
      </el-tabs>
    </div>
  </div>
</template>

<script>
import { getConfigByGroup, batchUpdateConfig } from '@/api/config'

export default {
  name: 'SystemConfig',
  data() {
    return {
      activeTab: 'basic',
      saveLoading: false,
      basicForm: {
        site_name: '',
        site_description: '',
        logo_url: '',
        default_language: 'en',
        registration_enabled: true,
        maintenance_mode: false
      },
      emailForm: {
        smtp_host: '',
        smtp_port: 587,
        smtp_username: '',
        smtp_password: '',
        smtp_encryption: 'tls',
        from_email: '',
        from_name: ''
      },
      storageForm: {
        driver: 'local',
        max_size: 10,
        allowed_extensions: 'jpg,png,gif,pdf,doc,docx',
        s3_bucket: '',
        s3_region: '',
        s3_access_key: '',
        s3_secret_key: '',
        oss_bucket: '',
        oss_endpoint: '',
        oss_access_key_id: '',
        oss_access_key_secret: ''
      }
    }
  },
  created() {
    this.loadConfig('basic')
    this.loadConfig('email')
    this.loadConfig('storage')
  },
  methods: {
    loadConfig(group) {
      getConfigByGroup(group).then(res => {
        const data = res.data || {}
        if (group === 'basic') {
          Object.keys(this.basicForm).forEach(key => {
            if (data[key] !== undefined) {
              this.basicForm[key] = data[key]
            }
          })
        } else if (group === 'email') {
          Object.keys(this.emailForm).forEach(key => {
            if (data[key] !== undefined) {
              this.emailForm[key] = data[key]
            }
          })
        } else if (group === 'storage') {
          Object.keys(this.storageForm).forEach(key => {
            if (data[key] !== undefined) {
              this.storageForm[key] = data[key]
            }
          })
        }
      }).catch(() => {})
    },
    handleTabChange(tab) {
      this.loadConfig(tab.name)
    },
    handleSave(group) {
      this.saveLoading = true
      let data
      if (group === 'basic') data = this.basicForm
      else if (group === 'email') data = this.emailForm
      else data = this.storageForm

      batchUpdateConfig({ group, data }).then(() => {
        this.$message.success('配置保存成功')
      }).catch(() => {}).finally(() => {
        this.saveLoading = false
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/styles/variables.scss';

.config-form {
  max-width: 640px;
  padding-top: 20px;
}
</style>
