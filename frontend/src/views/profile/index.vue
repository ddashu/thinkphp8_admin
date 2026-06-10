<template>
  <div class="page-container">
    <el-row :gutter="16">
      <!-- Profile Card -->
      <el-col :xs="24" :md="8">
        <div class="profile-card content-card">
          <div class="avatar-section">
            <div class="avatar-wrapper">
              <div class="avatar-placeholder">
                <i class="el-icon-user-solid" />
              </div>
              <el-upload
                :action="uploadUrl"
                :headers="uploadHeaders"
                :show-file-list="false"
                :on-success="handleAvatarSuccess"
                class="avatar-uploader"
              >
                <div class="avatar-overlay">
                  <i class="el-icon-camera" />
                </div>
              </el-upload>
            </div>
            <h3 class="profile-name">{{ userInfo.nickname || userInfo.username || '管理员' }}</h3>
            <p class="profile-role">{{ userInfo.role_name || '管理员' }}</p>
          </div>
          <div class="profile-details">
            <div class="detail-item">
              <i class="el-icon-user"></i>
              <span>{{ userInfo.username || '-' }}</span>
            </div>
            <div class="detail-item">
              <i class="el-icon-message"></i>
              <span>{{ userInfo.email || '-' }}</span>
            </div>
            <div class="detail-item">
              <i class="el-icon-phone"></i>
              <span>{{ userInfo.phone || '-' }}</span>
            </div>
            <div class="detail-item">
              <i class="el-icon-time"></i>
              <span>注册时间：{{ userInfo.create_time || '-' }}</span>
            </div>
          </div>
        </div>
      </el-col>

      <!-- Edit Forms -->
      <el-col :xs="24" :md="16">
        <!-- Edit Profile -->
        <div class="content-card mb-16">
          <h3 class="section-title">编辑资料</h3>
          <el-form ref="profileForm" :model="profileForm" :rules="profileRules" label-width="100px" size="small" class="edit-form">
            <el-form-item label="昵称" prop="nickname">
              <el-input v-model="profileForm.nickname" placeholder="请输入昵称" />
            </el-form-item>
            <el-form-item label="邮箱" prop="email">
              <el-input v-model="profileForm.email" placeholder="请输入邮箱" />
            </el-form-item>
            <el-form-item label="手机号" prop="phone">
              <el-input v-model="profileForm.phone" placeholder="请输入手机号" />
            </el-form-item>
            <el-form-item>
              <el-button type="primary" :loading="profileLoading" @click="handleProfileSave">保存修改</el-button>
            </el-form-item>
          </el-form>
        </div>

        <!-- Change Password -->
        <div class="content-card">
          <h3 class="section-title">修改密码</h3>
          <el-form ref="passwordForm" :model="passwordForm" :rules="passwordRules" label-width="100px" size="small" class="edit-form">
            <el-form-item label="当前密码" prop="old_password">
              <el-input v-model="passwordForm.old_password" type="password" placeholder="请输入当前密码" show-password />
            </el-form-item>
            <el-form-item label="新密码" prop="new_password">
              <el-input v-model="passwordForm.new_password" type="password" placeholder="请输入新密码" show-password />
            </el-form-item>
            <el-form-item label="确认密码" prop="confirm_password">
              <el-input v-model="passwordForm.confirm_password" type="password" placeholder="请确认新密码" show-password />
            </el-form-item>
            <el-form-item>
              <el-button type="primary" :loading="passwordLoading" @click="handlePasswordSave">修改密码</el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { getProfile, updateProfile, updatePassword, uploadAvatar } from '@/api/profile'
import { getToken } from '@/utils/auth'

export default {
  name: 'Profile',
  data() {
    const validateConfirmPassword = (rule, value, callback) => {
      if (value !== this.passwordForm.new_password) {
        callback(new Error('Passwords do not match'))
      } else {
        callback()
      }
    }
    return {
      profileLoading: false,
      passwordLoading: false,
      profileForm: {
        nickname: '',
        email: '',
        phone: ''
      },
      profileRules: {
        nickname: [{ required: true, message: '请输入昵称', trigger: 'blur' }]
      },
      passwordForm: {
        old_password: '',
        new_password: '',
        confirm_password: ''
      },
      passwordRules: {
        old_password: [{ required: true, message: '请输入当前密码', trigger: 'blur' }],
        new_password: [
          { required: true, message: '请输入新密码', trigger: 'blur' },
          { min: 6, message: '密码长度不能少于6位', trigger: 'blur' }
        ],
        confirm_password: [
          { required: true, message: '请确认新密码', trigger: 'blur' },
          { validator: validateConfirmPassword, trigger: 'blur' }
        ]
      }
    }
  },
  computed: {
    ...mapGetters(['userInfo']),
    uploadUrl() {
      return process.env.VUE_APP_BASE_API + '/profile/avatar'
    },
    uploadHeaders() {
      return { Authorization: 'Bearer ' + getToken() }
    }
  },
  created() {
    this.loadProfile()
  },
  methods: {
    loadProfile() {
      getProfile().then(res => {
        const data = res.data || {}
        this.profileForm = {
          nickname: data.nickname || '',
          email: data.email || '',
          phone: data.phone || ''
        }
      }).catch(() => {})
    },
    handleAvatarSuccess(response) {
      this.$message.success('头像已更新')
      this.$store.dispatch('user/getInfo')
    },
    handleProfileSave() {
      this.$refs.profileForm.validate(valid => {
        if (!valid) return
        this.profileLoading = true
        updateProfile(this.profileForm).then(() => {
          this.$message.success('资料更新成功')
          this.$store.dispatch('user/getInfo')
        }).catch(() => {}).finally(() => { this.profileLoading = false })
      })
    },
    handlePasswordSave() {
      this.$refs.passwordForm.validate(valid => {
        if (!valid) return
        this.passwordLoading = true
        updatePassword(this.passwordForm).then(() => {
          this.$message.success('密码修改成功')
          this.passwordForm = { old_password: '', new_password: '', confirm_password: '' }
          this.$nextTick(() => { this.$refs.passwordForm && this.$refs.passwordForm.clearValidate() })
        }).catch(() => {}).finally(() => { this.passwordLoading = false })
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/styles/variables.scss';

.profile-card {
  text-align: center;
}

.avatar-section {
  padding: 24px 0 16px;
}

.avatar-wrapper {
  position: relative;
  width: 96px;
  height: 96px;
  margin: 0 auto 16px;
}

.avatar-placeholder {
  width: 96px;
  height: 96px;
  border-radius: 50%;
  background: linear-gradient(135deg, $primary-color, $primary-light);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 40px;
}

.avatar-uploader {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  overflow: hidden;
  cursor: pointer;

  .avatar-overlay {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
    color: #fff;
    font-size: 24px;
  }

  &:hover .avatar-overlay {
    opacity: 1;
  }
}

.profile-name {
  font-size: 20px;
  font-weight: 700;
  color: $text-primary;
  margin-bottom: 4px;
}

.profile-role {
  font-size: 14px;
  color: $text-secondary;
}

.profile-details {
  border-top: 1px solid $border-light;
  padding: 16px 20px;
}

.detail-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 0;
  font-size: 14px;
  color: $text-regular;

  i {
    font-size: 16px;
    color: $text-secondary;
    width: 20px;
    text-align: center;
  }
}

.section-title {
  font-size: 16px;
  font-weight: 600;
  color: $text-primary;
  margin-bottom: 20px;
  padding-bottom: 12px;
  border-bottom: 1px solid $border-light;
}

.edit-form {
  max-width: 480px;
}
</style>
