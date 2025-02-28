<template>
    <div class="auth-container">
      <div class="forgot-password-card">
        <h2>Busca de Endereço</h2>
        <select v-model="form.tipoPesquisa" class="w-full">
            <option value="cep">CEP</option>
            <option value="endereco">Endereço</option>
        </select>
        <div class="auth-form" v-if="form.tipoPesquisa == 'endereco'">
          <div class="form-group">
            <div>
              <label for="uf">UF</label>
              <input
                type="text"
                id="uf"
                v-model="uf"
                required
                placeholder="Digite a UF"
              />
            </div>
            <div>
              <label for="cidade">Cidade</label>
              <input
                type="text"
                id="cidade"
                v-model="cidade"
                required
                placeholder="Digite a cidade"
              />
            </div>
            <div>
              <label for="logradouro">Logradouro</label>
              <input
                type="text"
                id="logradouro"
                v-model="logradouro"
                required
                placeholder="Digite logradouro ou CEP"
              />
            </div>
          </div>
        </div>

        <div class="auth-form" v-if="form.tipoPesquisa == 'endereco' && form.address.length != 0">
            <label>Selecione seu endereço</label>
          <div class="form-group">
            <select v-model="form.addressSelected" @change="selectAdress()">
                <option v-for="ad in form.address" :value="ad">{{ ad.cep }} - {{ ad.logradouro }} - {{ ad.bairro }}</option>
            </select>
          </div>
        </div>

        <div class="auth-form" v-if="form.tipoPesquisa == 'cep'">
            <div class="form-group">
                <div>
                    <label for="cep">CEP</label>
                    <input
                        type="text"
                        id="cep"
                        v-model="cep"
                        required
                        placeholder="Digite o CEP"
                        maxlength="9"
                        inputmode="numeric"
                    />
                </div>
            </div>
        </div>
        <div class="links">
          <button @click="searchAddress" class="button-submit">Buscar Endereço</button>
        </div>

        <h2 class="form-title">Cadastro de Contato</h2>
        
        <div class="auth-form">
          <div class="form-group">
            <div>
              <label for="nome">Nome</label>
              <input
                type="text"
                id="nome"
                v-model="form.nome"
                required
                placeholder="Digite o nome"
              />
            </div>
            <div>
              <label for="cpf">CPF</label>
              <input
                type="text"
                id="cpf"
                v-model="form.cpf"
                required
                placeholder="Digite o CPF"
                maxlength="14"
              />
            </div>
            <div>
              <label for="telefone">Telefone</label>
              <input
                type="text"
                id="telefone"
                v-model="form.telefone"
                required
                placeholder="Digite o telefone"
              />
            </div>
            <div>
              <label for="cep">CEP</label>
              <input
                type="text"
                id="cep"
                v-model="form.cep"
                required
                placeholder="CEP"
                readonly
              />
            </div>
            <div>
              <label for="endereco">Endereço</label>
              <input
                type="text"
                id="endereco"
                v-model="form.endereco"
                required
                placeholder="Endereço"
                readonly
              />
            </div>
            <div>
              <label for="numero">Número</label>
              <input
                type="text"
                id="numero"
                v-model="form.numero"
                required
                placeholder="Número"
              />
            </div>
            <div>
              <label for="bairro">Bairro</label>
              <input
                type="text"
                id="bairro"
                v-model="form.bairro"
                required
                placeholder="Bairro"
                readonly
              />
            </div>
            <div>
              <label for="cidadeContato">Cidade</label>
              <input
                type="text"
                id="cidadeContato"
                v-model="form.cidade"
                required
                placeholder="Cidade"
                readonly
              />
            </div>
            <div>
              <label for="ufContato">UF</label>
              <input
                type="text"
                id="ufContato"
                v-model="form.uf"
                required
                placeholder="UF"
                readonly
              />
            </div>
          </div>
        </div>
        <div class="links">
          <button @click="submit()" class="button-submit">Cadastrar Contato</button>
        </div>
        <div class="links">
          <a href="/" class="back-link">Voltar</a>
        </div>
      </div>
    </div>
  </template>
  
  
  <script setup>
  import { ref, watch } from 'vue';
  import axios from 'axios';
  import { useForm } from '@inertiajs/inertia-vue3';
  import '../../../css/create.css'; 
  import { useToast } from 'vue-toastification'; 
  
  const uf = ref('');
  const cidade = ref('');
  const logradouro = ref('');
  const cep = ref('');

  const toast = useToast();
  
  const form = useForm({
    tipoPesquisa: '',
    nome: '',
    cpf: '',
    telefone: '',
    cep: '',
    endereco: '',
    numero: '',
    bairro: '',
    cidade: '',
    uf: '',
    address: [],
    addressSelected: [],
  })  

  function formatCEP(value) {
        let cepValue = value.replace(/\D/g, '');
        
        if (cepValue.length > 5) {
            cepValue = cepValue.replace(/^(\d{5})(\d)/, '$1-$2');
        }
        
        return cepValue.substring(0, 9);
    }

  function formatCPF(value) {
    let cpf = value.replace(/\D/g, '');
    
    if (cpf.length > 3) cpf = cpf.replace(/^(\d{3})(\d)/, '$1.$2');
    if (cpf.length > 7) cpf = cpf.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
    if (cpf.length > 11) cpf = cpf.replace(/\.(\d{3})(\d)/, '.$1-$2');
    
    return cpf.substring(0, 14);
  }

    watch(
        () => form.cpf,
        (newValue) => {
            const formatted = formatCPF(newValue || '');
            if (formatted !== newValue) {
                form.cpf = formatted;
            }
        }
    );

    watch(
        () => cep.value,
        (newValue) => {
            const formatted = formatCEP(newValue || '');
            if (formatted !== newValue) {
                cep.value = formatted;
            }
        }
    );
    
  function searchAddress() {
    if(form.tipoPesquisa == 'cep'){
        axios.get('/api/searchCep', {
            params: { cep: cep.value }
        }).then(function(response){
            form.cep = response.data.cep;
            form.endereco = response.data.logradouro;
            form.bairro = response.data.bairro;
            form.cidade = response.data.localidade;
            form.uf = response.data.uf;
        });
    }
     else {
        axios.get('/api/searchAddress', { 
            params: {
            uf: uf.value,
            cidade: cidade.value,
            logradouro: logradouro.value
            }
    }).then(function(response){
            form.address = response.data            
        });
      
    }  
  }

  function submit() {
  axios.post(route('store.contact'), form)
    .then(response => {
      toast.success('Cadastro realizado com sucesso!');
      form.reset();
    })
    .catch(error => {
      if (error.response && error.response.status === 422) {
        const errors = Object.values(error.response.data.errors)
          .flat()
          .join('\n');
        toast.error(errors);
      } else {
        toast.error("Ocorreu um erro inesperado!");
      }
    });
}

  function selectAdress(){
    form.cep = form.addressSelected.cep;
    form.endereco = form.addressSelected.logradouro;
    form.bairro = form.addressSelected.bairro;
    form.cidade = form.addressSelected.localidade;
    form.uf = form.addressSelected.uf;
  }
  

  </script>
  
  <style scoped>
  .auth-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    color: black;
  }
  
  .forgot-password-card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
    text-align: center;
  }
  
  .forgot-password-card h2 {
    color: #1a1a1a;
    margin-bottom: 1rem;
  }

  .form-title {
    margin-top: 2rem;
    color: #1a1a1a;
  }
  
  .auth-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-top: 1rem;
  }
  
  .form-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    text-align: left;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #333;
  }
  
  .form-group input {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
  }

  input[readonly] {
    background-color: #f5f5f5;
    cursor: not-allowed;
  }
  
  .button-submit {
    background-color: #007bff;
    color: white;
    padding: 0.8rem 2rem;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-top: 1rem;
  }
  
  .button-submit:hover {
    background-color: #0056b3;
  }

  .back-link {
    color: #007bff;
    text-decoration: none;
    font-size: 0.9rem;
    display: block;
    margin-top: 1rem;
  }
  
  .back-link:hover {
    text-decoration: underline;
  }

  .links {
    margin-top: 1.5rem;
  }
  </style>