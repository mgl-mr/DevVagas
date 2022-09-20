<template>
  <div :class="bannerClass">
    <img src="/img/logo.png" alt="logo Dev Vagas" id="logo">
    <h1>Dev Vagas</h1>
  </div>
</template>
B
<script>
  export default {
    name: "Banner",

    props: ["bannerClass"],
  }
</script>

<style scoped>
.banner #logo {
  margin: 0px;
  width: 90%;
}

.login #logo {
  margin: 0px;
  width: 250px;
}

.banner, .login {
  font-family: helvetica;
  font-size: 18px;
  color: #9af995;
  line-height: 70px;
  text-align: center;
  padding: 10px
}

.banner h1{
  margin-top: -40px;
}

@media screen and (max-width: 800px) {
  .banner {
    height: 60px;
    padding: 0px;
  }

  .banner h1{
    opacity: 0;
    visibility: hidden;
  }
}

</style>