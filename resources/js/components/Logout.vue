<template>
  <div>
    <a class="nav-link" href="#" @click.prevent="logout">
      <i class="nav-icon fas fa-power-off text-red"></i>
      <p>{{ __('Logout') }}</p>
    </a>
  </div>
</template>

<script>
export default {
  methods: {
    logout() {
      document.getElementById("logout-form").submit();
    }
  }
};
</script>
