<template>
  <div class="view">
    <div class="header">
      <h2>Meus envios</h2>
    </div>
    <div class="msg-container msg-container-styck">
      <transition>
        <p v-show="msg.error" class="msg msg-styck">{{ msg.mensagem }}</p>
      </transition>
    </div>
    <div v-if="render.error == false" class="warning">
      <div>
        <p>{{render.mensagem}}</p>
      </div>
    </div>
    <div v-else>
      <div class="container" v-show="vagas != null">
        <div v-for="vaga in vagas" :key="vaga.id_vaga" class="field">
          <div class="header sub-header">
            <h2>Vaga</h2>
          </div>
          <div class="flex">
            <div class="flex-1">
              <h3>Sobre a empresa</h3>
              <div class="text">
                <p>Empresa: <i>{{vaga.nome}}</i></p>
                <p>Email: <i>{{vaga.email}}</i></p>
                <p>Telefone: <i>{{vaga.phone}}</i></p>
              </div>
            </div>
            <div class="flex-2">
              <h3>Endereço</h3>
              <div class="text">
                <div>
                  <p>Logradouro: <i>{{vaga.logradouro}}</i></p>
                  <p>Complemento: <i>{{vaga.complemento}}</i></p>
                  <p>Bairro: <i>{{vaga.bairro}}</i></p>
                </div>
                <div>
                  <p>Cidade: <i>{{vaga.cidade}}</i></p>
                  <p>Estado: <i>{{vaga.estado}}</i></p>
                </div>
              </div>
            </div>
            <div class="flex-3">
              <h3>Sobre a vaga</h3>
              <div>
                <div class="text">
                  <p>Área: <i>{{vaga.area}}</i></p>
                  <p>Vaga: <i>{{vaga.vaga}}</i></p>
                  <p>Salário: <i>{{vaga.salario}}</i></p>
                </div> 
                <h3 class="flex3">Descrição</h3>
                <p>{{vaga.descricao}}</p>
                <h3 class="flex3">Requisitos</h3>
                <p>{{vaga.requisitos}}</p>
              </div> 
            </div> 
          </div>
       
          <button type="button" class="btn" @click="deleteEnvio(vaga.id_vaga)">Cancelar envio</button>
        </div> 
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      chave: "",
      id_curriculo: null,
      render: {error: false, mensagem: ""},
      msg: {error: false, mensagem: ""},
      vagas: null,
    }
  },

  methods: {
    buscaEnvios(){
      let formData = new FormData();
      formData.append("chave", this.chave);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaEnvios", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { 
        if(data.error) {
          this.render.error = false;
          this.render.mensagem = data.message;
          this.vagas = null;
        } else {
          this.render.error = true;
          this.id_curriculo = data.id_curriculo;
          this.vagas = data.vagas;
        }
      })
    },

    deleteEnvio(id){
      let formData = new FormData();
      formData.append("id_curriculo", this.id_curriculo);
      formData.append("id_vaga", id);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=deleteEnvio", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { 
        this.msg.error = true;
        this.msg.mensagem = data.message;
        if(data.error == false) {
          for(let i=0; i<this.vagas.length; i++) {
            if(this.vagas[i].id_vaga == id) {
              this.vagas.splice(i, 1);
            }
          }
          if(this.vagas == "") {
            this.render.error = false;
            this.render.mensagem = "Nenhum curriculo enviado";
          }
        }
      });
      setTimeout(() => {
        (this.msg.error = false), (this.msg.mensagem = "");
      }, 2000); 
    },

    checaVariaveis() {
      this.chave = localStorage.getItem("chave");
    }
  },

  created() {
    this.checaVariaveis();
    this.buscaEnvios();
  }
}
</script>

<style scoped>
.view {
  height: auto;
}

.container {
  color: #303133;
  margin-left: 15px;
  margin-top: 15px;
  flex-direction: column;
  padding-right: 15px;
}

.container .field{
  margin-bottom: 15px;
  width: 100%;
}

.container .field .flex{
  display: flex;
  flex-direction: column;
  width: 100%;
  gap: 15px;
  padding-top: 5px;
}

.container .field .flex > div{
  padding-left: 10px;
}

.container .field .flex .text{
  display: flex;
  justify-content: space-evenly ;
  height: auto;
}

.container .field .flex div h3{
  font-size: 16px;
  font-family: Arial, Helvetica, sans-serif;
  border-bottom: 3px solid #303133;
  margin-bottom: 5px;
  margin-top: 10px;
}

.container .field .flex div .flex3 {
  margin-top: 10px;
  margin-bottom: 2px;
}

.container .field .flex div p {
  font-family: Arial, Helvetica, sans-serif;
  font-weight: 500;
  color: #303133;
  font-size: 18px;  
}

.container .field .flex div p i {
  color: #4a4b4f;
}

.container .field .btn {
  margin-top: 15px;
}

.warning {
  width: 100%;
  height: 80vh;
}

.sub-header {
  margin-bottom: 10px;
}

.sub-header h2 {
  font-size: 28px;
  text-align: left;
  margin-left: 0;
}

.msg-styck {
  top: 50px;
}

/*** MEDIA QUERIES ***/
@media screen and (max-width: 800px) {
  .view {
    height: auto;
  }

  .container .field .flex {
    display: block;
  }

  .container .field .flex div{
    width: 100%;  
  }

  .container .field .flex div p {
    margin-bottom: 5px;
  }

  .container .field .flex .text{
    display: block;
  }

  .warning {
    height: 85vh;
  }
}
</style>