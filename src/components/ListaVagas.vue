<template>
  <div class="view">
    <div class="msg-container">
      <transition>
        <p v-show="msg.error" class="msg msg-styck">{{ msg.mensagem }}</p>
      </transition>
    </div>

    <div class="curriculos" v-if="show">
      <div class="header sub-header">
        <h2>Curriculos</h2>
        <button type="button" class="bt" @click="fechaCurriculo">
          <i class="fa-solid fa-x"></i>
        </button>
      </div>
      <div v-if="render.error == false">
        <p class="empty">{{render.mensagem}}</p>
      </div>
      <div v-else class="container">
        <div v-for="curriculo in curriculos" :key="curriculo.id" class="field">
          <div class="header sub-header">
            <h2>{{curriculo.nome}}</h2>
          </div>

          <h3>Contato</h3>
          <div class="flex">
            <p>Email: {{curriculo.email}}</p>
            <p>Telefone: {{curriculo.phone}}</p>
          </div>

          <h3>GitHub</h3>
          <div class="flex">           
            <p>GitHub: {{curriculo.github}}</p>
            <p>Data de nascimento: {{curriculo.data_nasc}}</p>
          </div>

          <h3>Endereço</h3>
          <div class="flex">   
            <p>Cidade: {{curriculo.cidade}}</p>
            <p>Estado: {{curriculo.estado}}</p>
          </div>

          <h3>Idiomas</h3>
          <div class="center">            
            <p>{{curriculo.idiomas}}</p>
          </div>

          <h3>Formação</h3>
          <div class="center">     
            <p>{{curriculo.formacao}}</p>
          </div>

          <h3>Experiência</h3>
          <div class="center">           
            <p>{{curriculo.experiencia}}</p>
          </div>
        </div>
      </div>
    </div>

    <div v-else>
      <div class="header sub-header">
        <h2>Vagas cadastradas</h2>
      </div>
      <p v-if="vagas == true">Não há vagas cadastradas</p>
      <div v-else class="container">
        <div v-for="vaga in vagas" :key="vaga.id" class="field">

          <div class="container-input">
            <label for="area">Área de atuação</label>
            <select name="area" id="area" v-model="vaga.area" class="input">
              <option value="" disabled>Selecione á área</option>
              <option value="Desenvolvedor Front-End">Desenvolvedor Front-End</option>
              <option value="Desenvolvedor Back-End">Desenvolvedor Back-End</option>
              <option value="Desenvolvedor Full-stack">Desenvolvedor Full-stack</option>
              <option value="Desenvolvedor mobile">Desenvolvedor mobile</option>
              <option value="Cientista de dados">Cientista de dados</option>
              <option value="Técnico de segurança">Técnico de segurança</option>
            </select>
          </div>
          
          <div class="container-input">
            <label for="vaga">Tipo da vaga</label>
            <input type="radio" id="efetivado" name="vaga" value="efetivado" v-model="vaga.vaga">
            <label for="efetivado">Efetivado</label>
            <input type="radio" id="freelance" name="vaga" value="freelance" v-model="vaga.vaga">
            <label for="freelance">Freelance</label>
          </div>

          <div class="container-input">
            <label for="descrição">Descrição da vaga</label>
            <textarea class="input" name="descrição"  v-model="vaga.descricao" placeholder="De algumas informações da vaga"></textarea>
          </div>

          <div class="container-input">
            <label for="requisitos">Requisitos da vaga</label>
            <textarea class="input" name="requisitos"  v-model="vaga.requisitos" placeholder="Liste os requisitos da vaga"></textarea>
          </div>

          <div class="container-input">
            <label for="salario">Salario</label>
            <input type="text" class="input" name="salario" v-model="vaga.salario" placeholder="Informe o salário">
          </div>
          <div class="botoes">
            <button type="button" class="btn" @click="updateVaga(vaga)">Alterar vaga</button>
            <button type="button" class="btn" @click="deleteVaga(vaga.id)">Deletar vaga</button>
            <button type="button" class="btn" @click="mostraCurriculo(vaga.id)">Curriculos</button>
          </div>
        </div>
      </div> 
    </div>
  </div>
</template>

<script>

export default {
  name: "ListaVagas",
  props: ["id"],

  data() {
    return {
      show: false,
      id_vaga: null,
      vagas: null,
      msg: {error: false, mensagem: ""},
      render: {error: false, mensagem: ""},
      curriculos: null,
    }
  },

  methods: {
    buscaVagas() {
      let formData = new FormData();
      formData.append("id", this.id);
      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaVagas", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { 
        if(data.error) {
          this.vagas = true
        } else {
          this.vagas = data.vagas;
        }
      });
    },

    updateVaga(vaga) {
      let formData = new FormData();
      formData.append("id", vaga.id);
      formData.append("area", vaga.area);
      formData.append("vaga", vaga.vaga);
      formData.append("requisitos", vaga.requisitos);
      formData.append("descricao", vaga.descricao);
      formData.append("salario", vaga.salario);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=updateVaga", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { 
        this.msg.error = true;
        this.msg.mensagem = data.message;
      });
      setTimeout(() => {
        (this.msg.error = false), (this.msg.mensagem = "");
      }, 2000);
    },

    deleteVaga(id){
      let formData = new FormData();
      formData.append("id", id);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=deleteVaga", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { 
        this.msg.error = true;
        this.msg.mensagem = data.message;
        if(data.error == false) {
          for(let i=0; i<this.vagas.length; i++) {
            if(this.vagas[i].id == id) {
              this.vagas.splice(i, 1);
            }
          }
          if(this.vagas == "") {
            this.vagas = true;
          }
        }
      });
      setTimeout(() => {
        (this.msg.error = false), (this.msg.mensagem = "");
      }, 2000); 
    },

    buscaCurriculos() {
      let formData = new FormData();
      formData.append("id_vaga", this.id_vaga);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaCurriculos", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { 
        if(data.error) { 
          this.render.error = false;
          this.render.mensagem = data.message;
        } else {
          this.render.error = true;
          this.curriculos = data.curriculos;
        }
      }) 
    },

    mostraCurriculo(id) {
      this.id_vaga = id;
      this.show = true;
      this.buscaCurriculos();
    },

    fechaCurriculo() {
      this.show = false;
      this.id_vaga = null;
      this.curriculos = null;
    }
  },

  created() {
    this.buscaVagas();
  }
}
</script>

<style scoped>
.view {
  margin: 0;
  width: 100%;
}

.container {
  flex-wrap: wrap;
  color: #303133;
  margin-left: 2%;
  margin-top: 15px;
}

.field {
  width: 47%;
  margin-right: 2%;
  margin-bottom: 15px;
}

.botoes {
  display: flex;
  justify-content: center;
  align-items: center;
}

.btn{
  width: 30%;
  font-size: 10px;
  margin-left: 2px;
}

.view > p {
  text-align: center;
  font-family: Arial, Helvetica, sans-serif;
  height: 50px;
  padding-top: 12px;
}

.msg-styck {
  top: 50px;
}

/*** LISTA CURRICULOS */
.curriculos {
  
}

.curriculos .field{
  width: 100%;
}

.curriculos .field .flex{
  display: flex;
  justify-content: space-around;
}

.curriculos .field .center{
  text-align: center;
}

.curriculos .header h2 {
  font-size: 38px;
}

.curriculos .field h3{
  font-size: 16px;
  font-family: Arial, Helvetica, sans-serif;
  border-bottom: 3px solid #303133;
  margin-bottom: 5px;
  margin-top: 10px;
}

.curriculos .field h2 {
  font-size: 24px;
  text-align: left;
  margin-left: 0;
}

.curriculos .field p {
  font-family: Arial, Helvetica, sans-serif;
  font-weight: 500;
  color: #303133;
  font-size: 18px;  
}

.curriculos .header {
  position: relative;
}

.curriculos .bt {
  position: absolute;
  left: 10px;
  top: 20px;
  border: none;
  background-color: transparent;
  font-size: 28px;
}

.curriculos .bt:hover{
  cursor: pointer;
  color: #9af995;
}

.empty {
  font-family: Arial, Helvetica, sans-serif;
  font-weight: 500;
  color: #4a4b4f;
  font-size: 18px;
  margin-bottom: 10px;
  border-bottom: 2px solid #dfe2ed;
  padding-bottom: 3px;
  text-align: center;
}

@media screen and (max-width: 800px) {
  .container {
    display: block; 
    padding-right: 15px;
  }

  .field {
    width: 100%;
    margin-bottom: 15px;
  }

  .header h2 {
    font-size: 24px;
  }

  .curriculos .field .flex{
    display: block;
  }

  .curriculos .field .center{
    text-align: left;
  }

}
</style>