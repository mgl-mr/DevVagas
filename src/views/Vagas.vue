<template>
  <div class="view">
    <div v-if="completo != '1'" class="warning">
      <div class="header">
        <h2>AVISO</h2>
      </div>
      <div>
        <p>Você ainda não completou seu perfil</p>
        <p>Vá em "perfil" e cadastre seu endereço</p>
      </div>
    </div>
    <div v-else>
      <div>
        <div class="header">
        <h2>Criar vaga</h2>
        </div>
        <div class="container">
          <EmpData :chave="chave" />

          <div class="field">
            <div class="container-input">
              <label for="area">Ecolha a área de atuação</label>
              <select name="area" id="area" v-model="vaga.area" class="input">
                <option value="" disabled>Selecione a área</option>
                <option value="Desenvolvedor Front-End">Desenvolvedor Front-End</option>
                <option value="Desenvolvedor Back-End">Desenvolvedor Back-End</option>
                <option value="Desenvolvedor Full-stack">Desenvolvedor Full-stack</option>
                <option value="Desenvolvedor mobile">Desenvolvedor mobile</option>
                <option value="Cientista de dados">Cientista de dados</option>
                <option value="Técnico de segurança">Técnico de segurança</option>
              </select>
            </div>

            <div class="container-input">
              <label for="vaga">Ecolha o tipo da vaga</label>
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

            <div class="msg-container">
              <transition>
                <p v-show="msg.error" class="msg">{{ msg.mensagem }}</p>
              </transition>
            </div>
            <button type="button" class="btn" @click="inserirVaga">Inserir vaga</button>
          </div>
        </div>
      </div>
      <ListaVagas :id="id"/>
    </div>
  </div>
</template>

<script>
import EmpData from '../components/EmpData';
import ListaVagas from '../components/ListaVagas';

export default {
  components: {
    EmpData,
    ListaVagas
  },
    
  data() {
    return {
      completo: false,
      chave: null,
      id: null,
      msg: {error: false, mensagem: ""},
      msg2: {error: false, mensagem: ""},
      vaga: {
        area: "",
        vaga: "",
        descricao: "",
        requisitos: "",
        salario: "",
      }
    };
  },

  methods: {
    buscaEmp() {
      let formData = new FormData();
      formData.append("chave", this.chave);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaEmp", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { 
        if(data.error) { 
          this.completo = false;
        } else {
          this.completo = data.emp.completo;
          this.id = data.emp.id_empresa;
        }
      })
    },

    inserirVaga() {
      let formData = new FormData();
      formData.append("id", this.id);
      formData.append("area", this.vaga.area);
      formData.append("vaga", this.vaga.vaga);
      formData.append("descricao", this.vaga.descricao);
      formData.append("requisitos", this.vaga.requisitos);
      formData.append("salario", this.vaga.salario);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=inserirVaga", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { 
        if(data.error == false) {
          const obj = this.vaga;
          for (const campo in obj){
            obj[campo] = "";
          } 
          window.location.reload();
        } else {
          this.msg.error = true;
          this.msg.mensagem = data.message;
        }
      });
      setTimeout(() => {
        (this.msg.error = false), (this.msg.mensagem = "");
      }, 2000);
    },

    checaVariaveis(){
      this.chave = localStorage.getItem("chave");
    }
  },

  created() {
    this.checaVariaveis();
    this.buscaEmp();
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
}

.field {
  width: 50%;
  margin-right: 15px;
}

/*** MEDIA QUERRIES ***/
@media screen and (max-width: 800px) {
  .container {
    display: block; 
    padding-right: 15px;
  }

  .field {
    width: 100%;
    margin-bottom: 15px;
  }
}

</style>