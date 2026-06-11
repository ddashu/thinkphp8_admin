<template>
  <div class="login-container">
    <!-- Left brand area -->
    <div class="login-brand">
      <div class="brand-decoration">
        <div class="deco-circle deco-circle-1"></div>
        <div class="deco-circle deco-circle-2"></div>
        <div class="deco-circle deco-circle-3"></div>
        <div class="deco-line deco-line-1"></div>
        <div class="deco-line deco-line-2"></div>
        <div class="deco-line deco-line-3"></div>
        <div class="deco-dot deco-dot-1"></div>
        <div class="deco-dot deco-dot-2"></div>
        <div class="deco-dot deco-dot-3"></div>
        <div class="deco-dot deco-dot-4"></div>
        <div class="deco-dot deco-dot-5"></div>
        <div class="deco-dot deco-dot-6"></div>
      </div>
      <div class="brand-content">
        <div class="brand-logo">
          <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="64" height="64" rx="16" fill="rgba(255,255,255,0.15)"/>
            <path d="M16 20h8l8 12-8 12h-8l8-12-8-12zm16 0h8l8 12-8 12h-8l8-12-8-12z" fill="white" opacity="0.95"/>
          </svg>
        </div>
        <h1 class="brand-title">量子Admin</h1>
        <p class="brand-subtitle">智能AI平台管理系统</p>
        <div class="brand-features">
          <div class="feature-item">
            <i class="el-icon-connection"></i>
            <span>多模型AI集成</span>
          </div>
          <div class="feature-item">
            <i class="el-icon-lock"></i>
            <span>安全API密钥管理</span>
          </div>
          <div class="feature-item">
            <i class="el-icon-data-analysis"></i>
            <span>实时数据分析看板</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Right form area -->
    <div class="login-form-area">
      <div class="login-form-wrapper">
        <div class="form-header">
          <h2 class="form-title">欢迎回来</h2>
          <p class="form-desc">请登录您的账号</p>
        </div>

        <el-form
          ref="loginForm"
          :model="loginForm"
          :rules="loginRules"
          class="login-form"
          autocomplete="on"
          label-position="left"
        >
          <el-form-item prop="username">
            <el-input
              ref="username"
              v-model="loginForm.username"
              placeholder="请输入用户名,admin"
              name="username"
              type="text"
              tabindex="1"
              autocomplete="on"
              prefix-icon="el-icon-user"
              size="large"
            />
          </el-form-item>

          <el-form-item prop="password">
            <el-input
              ref="password"
              v-model="loginForm.password"
              type="password"
              placeholder="请输入密码,默认密码:admin123"
              name="password"
              tabindex="2"
              autocomplete="on"
              prefix-icon="el-icon-lock"
              size="large"
              show-password
              @keyup.enter.native="handleLogin"
            />
          </el-form-item>

          <div class="form-options">
            <el-checkbox v-model="rememberMe">记住密码</el-checkbox>
          </div>

          <el-button
            :loading="loading"
            type="primary"
            size="large"
            class="login-button"
            @click.native.prevent="handleLogin"
          >
            登 录
          </el-button>
        </el-form>

        <div class="form-footer">
          <p class="footer-line">本程序由量子软件工作室开发</p>
          <p class="footer-line">
            官网：<a href="http://www.dglzsoft.com" target="_blank" rel="noopener">www.dglzsoft.com</a>
          </p>
          <p class="footer-line">技术支持微信：yework2016</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
      loginForm: {
        username: '',
        password: ''
      },
      loginRules: {
        username: [{ required: true, message: '请输入用户名', trigger: 'blur' }],
        password: [{ required: true, message: '请输入密码', trigger: 'blur' }]
      },
      rememberMe: false,
      loading: false,
      redirect: undefined
    }
  },
  watch: {
    $route: {
      handler: function(route) {
        this.redirect = route.query && route.query.redirect
      },
      immediate: true
    }
  },
  methods: {
    handleLogin() {
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.loading = true
          this.$store.dispatch('user/login', this.loginForm)
            .then(() => {
              this.$router.push({ path: this.redirect || '/' })
              this.loading = false
            })
            .catch(() => {
              this.loading = false
            })
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/styles/variables.scss';

.login-container {
  display: flex;
  height: 100vh;
  width: 100%;
  overflow: hidden;
}

// Left brand area
.login-brand {
  flex: 0 0 45%;
  background: linear-gradient(135deg, #1A1A2E 0%, #2D3A6E 50%, #3B5BDB 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}

.brand-decoration {
  position: absolute;
  inset: 0;
  pointer-events: none;
}

.deco-circle {
  position: absolute;
  border-radius: 50%;
  border: 1px solid rgba(255, 255, 255, 0.08);
}

.deco-circle-1 {
  width: 400px;
  height: 400px;
  top: -100px;
  right: -100px;
}

.deco-circle-2 {
  width: 300px;
  height: 300px;
  bottom: -80px;
  left: -60px;
}

.deco-circle-3 {
  width: 200px;
  height: 200px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  border-color: rgba(255, 255, 255, 0.04);
}

.deco-line {
  position: absolute;
  background: rgba(255, 255, 255, 0.06);
}

.deco-line-1 {
  width: 120px;
  height: 1px;
  top: 25%;
  right: 15%;
  transform: rotate(-30deg);
}

.deco-line-2 {
  width: 80px;
  height: 1px;
  bottom: 30%;
  left: 10%;
  transform: rotate(45deg);
}

.deco-line-3 {
  width: 60px;
  height: 1px;
  top: 60%;
  right: 25%;
  transform: rotate(-15deg);
}

.deco-dot {
  position: absolute;
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.15);
}

.deco-dot-1 { top: 15%; left: 20%; }
.deco-dot-2 { top: 35%; right: 10%; }
.deco-dot-3 { bottom: 20%; left: 30%; }
.deco-dot-4 { top: 70%; right: 30%; }
.deco-dot-5 { bottom: 40%; left: 15%; }
.deco-dot-6 { top: 50%; right: 20%; width: 6px; height: 6px; background: rgba(255, 255, 255, 0.1); }

.brand-content {
  position: relative;
  z-index: 1;
  text-align: center;
  padding: 40px;
}

.brand-logo {
  margin-bottom: 32px;
  display: flex;
  justify-content: center;

  svg {
    width: 80px;
    height: 80px;
  }
}

.brand-title {
  font-size: 36px;
  font-weight: 700;
  color: #FFFFFF;
  letter-spacing: 1px;
  margin-bottom: 12px;
}

.brand-subtitle {
  font-size: 16px;
  color: rgba(255, 255, 255, 0.7);
  margin-bottom: 48px;
  letter-spacing: 0.5px;
}

.brand-features {
  text-align: left;
  display: inline-block;
}

.feature-item {
  display: flex;
  align-items: center;
  color: rgba(255, 255, 255, 0.8);
  font-size: 14px;
  margin-bottom: 16px;

  i {
    font-size: 20px;
    margin-right: 12px;
    color: rgba(255, 255, 255, 0.6);
  }
}

// Right form area
.login-form-area {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #FFFFFF;
  padding: 40px;
}

.login-form-wrapper {
  width: 100%;
  max-width: 400px;
}

.form-header {
  margin-bottom: 40px;
}

.form-title {
  font-size: 28px;
  font-weight: 700;
  color: $text-primary;
  margin-bottom: 8px;
}

.form-desc {
  font-size: 15px;
  color: $text-secondary;
}

.login-form {
  .el-form-item {
    margin-bottom: 22px;
  }

  .el-input__inner {
    height: 48px;
    border-radius: 10px;
    border-color: $border-base;
    font-size: 14px;

    &:focus {
      border-color: $primary-color;
      box-shadow: 0 0 0 3px rgba($primary-color, 0.1);
    }
  }

  .el-input__prefix {
    left: 12px;
    font-size: 18px;
    color: $text-secondary;
  }

  .el-input--prefix .el-input__inner {
    padding-left: 40px;
  }
}

.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;

  .el-checkbox__label {
    color: $text-regular;
    font-size: 13px;
  }
}

.login-button {
  width: 100%;
  height: 48px;
  border-radius: 10px;
  font-size: 16px;
  font-weight: 600;
  letter-spacing: 0.5px;
  background: linear-gradient(135deg, $primary-color, $primary-light);
  border: none;
  transition: all 0.3s;

  &:hover {
    background: linear-gradient(135deg, $primary-dark, $primary-color);
    box-shadow: 0 4px 16px rgba($primary-color, 0.4);
    transform: translateY(-1px);
  }

  &:active {
    transform: translateY(0);
  }
}

.form-footer {
  text-align: center;
  margin-top: 40px;

  .footer-line {
    color: $text-placeholder;
    font-size: 12px;
    line-height: 1.8;
    margin: 0;

    a {
      color: $text-secondary;
      text-decoration: none;
      transition: color 0.2s;

      &:hover {
        color: $primary-color;
        text-decoration: underline;
      }
    }
  }
}

@media screen and (max-width: 768px) {
  .login-container {
    flex-direction: column;
  }

  .login-brand {
    flex: 0 0 auto;
    height: 200px;
  }

  .brand-features {
    display: none;
  }

  .brand-title {
    font-size: 24px;
  }

  .brand-subtitle {
    margin-bottom: 0;
  }
}
</style>
