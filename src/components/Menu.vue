<template>
  <header class="sidebar">
    <div class="banner">
      <img src="/img/logo.png" alt="logo Dev Vagas" id="logo">
      <h1>Dev Vagas</h1>
    </div>
    <nav>
      <router-link to="/"> 
        <i class="fa-solid fa-house"></i>
        <span>Home</span> 
      </router-link>
      <router-link to="/perfil">
        <i class="fa-solid fa-user"></i>
        <span>Perfil</span>
      </router-link>
      <router-link to="/curriculo" v-show="dev == 'true'">
        <i class="fa-solid fa-file"></i>
        <span>Curriculo</span>
      </router-link>
      <router-link to="/envios" v-show="dev == 'true'">
        <i class="fa-solid fa-share-from-square"></i>
        <span>Envios</span>
      </router-link>
      <router-link to="/vagas" v-show="dev == 'false'">
        <i class="fa-solid fa-clipboard"></i>
        <span>Vagas</span>
      </router-link>
  
      
      <router-link to="/" @click="$emit('sair')">
        <i class="fa-solid fa-door-open"></i>
        <span>sair</span>
      </router-link>
    </nav>
  </header>
</template>

<script>
  export default {
    name: "Menu",
    
    emits: ["sair"],

    data() {
      return {
        chave: null,
        dev: null
      }
    },

    methods: {
      checaVariaveis() {
      this.chave = localStorage.getItem("chave");
      this.dev = localStorage.getItem("dev");
      },
    },

    created() {
      this.checaVariaveis();
    },
  };
</script>

<style scoped>

.sidebar {
  position: fixed;
  width: 23%;
  height: 100vh;
  background-color: #4A4B4F;
  transition: all .5s ease;
  left: 0;
  top: 0; 
}

.sidebar a {
  text-decoration: none;
  display: block;
  height: 65px;
  width: 100%;
  color: #FAFAFA;
  line-height: 65px;
  padding-left: 30px;
  box-sizing: border-box;
  border-bottom: 1px solid #3e3f42;
  border-top: 1px solid #DFE2ED;
  border-left: 5px solid transparent;
  font-family: 'Open Sans', sans-serif;
  transition: all .5s ease;
}

a:hover {
  border-left: 5px solid #9af995;
  color: #9af995;
}

.sidebar a i {
  font-size: 23px;
  margin-right: 16px;
}

.sidebar a span {
  letter-spacing: 1px;
  text-transform: uppercase;
}

/*** BANNER ***/
.banner #logo {
  margin: 0px;
  width: 90%;
}

.banner{
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
  .sidebar {
    width: 70px;
  }

  span {
    position: absolute;
    margin-left: 23px;
    opacity: 0;
    visibility: hidden;
  }

  .sidebar a {
    height: 60px;
    background-color: #3e3f42;
    border-right: 1px solid #DFE2ED;
    border-top: 0;
    margin-right: 1px;
  }

  .sidebar a i {
    margin-left: -10px;
  }

  a:hover {
    width: 200px;
    background-color: #4A4B4F;
  }

  .sidebar a:hover span {
    opacity: 1;
    visibility: visible;
  } 

  .sidebar a:hover i {
    font-size: 34px;
  } 

  /*** BANNER ***/
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