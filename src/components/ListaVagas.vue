<template>
  <div class="vagas">
    <div class="msg-container msg-container-styck">
      <transition>
        <p v-show="msg.error" class="msg msg-styck">{{ msg.mensagem }}</p>
      </transition>
    </div>
    <h2>Vagas cadastradas</h2>
    
    <p v-if="vagas == true">Não há vagas cadastradas</p>
    <div v-else class="lista-vaga">
      <div v-for="vaga in vagas" :key="vaga.id" class="row-vaga">

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
        <button type="button" class="btn" @click="updateVaga(vaga)">Alterar vaga</button>
        <button type="button" class="btn" @click="deleteVaga(vaga.id)">Deletar vaga</button>
        <button type="button" class="btn" @click="mostraCurriculo(vaga.id)">Curriculos enviados</button>
      </div>
    </div> 
    <div class="list-styck" v-show="show">
      <div class="header">
      <h1>Curriculos</h1>
        <button type="button" @click="fechaCurriculo">X</button>
      </div>
      <div v-if="render.error == false">
        <p>{{render.mensagem}}</p>
      </div>
      <div v-else class="lista-vaga">
        <div v-for="curriculo in curriculos" :key="curriculo.id" class="row-vaga">
          <p>Nome: {{curriculo.nome}}</p>
          <p>Email: {{curriculo.email}}</p>
          <p>Telefone: {{curriculo.phone}}</p>
          <p>Github: {{curriculo.github}}</p>
          <p>Data de nascimento: {{curriculo.data_nasc}}</p>
          <p>Cidade: {{curriculo.cidade}}</p>
          <p>Estado: {{curriculo.estado}}</p>
          <p>Idiomas: {{curriculo.idiomas}}</p>
          <p>Formação: {{curriculo.formacao}}</p>
          <p>Experiência: {{curriculo.experiencia}}</p>
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
.row-vaga {
  background-color: #4a4b4f;
  padding: 10px;
  margin-bottom: 10px;
  
}

.row-vaga label {
  color: #dfe2ed;
  
}

.list-styck {
  position: fixed;
  top: 20px;
  width: 100%; 
  background-color: #4a4b4f;
  color: #DFE2ED;
  padding: 10px;
}
</style>