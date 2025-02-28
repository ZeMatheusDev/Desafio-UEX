<template>
    <div class="auth-container">
      <div class="forgot-password-card">
        <h2>Criar Conta</h2>
  
        <div class="auth-form">
          <div class="form-group">
            <div>
              <label for="email">E-mail</label>
              <input
                type="email"
                id="email"
                v-model="form.email"
                required
                placeholder="Digite seu e-mail"
              />
            </div>
            <div>
              <label for="email">Senha</label>
              <input
                type="password"
                id="senha"
                v-model="form.senha"
                required
                placeholder="Digite sua senha"
              />
            </div>
            <div>
              <label for="email">Nome</label>
              <input
                type="text"
                id="nome"
                v-model="form.nome"
                required
                placeholder="Digite seu nome"
              />
            </div>
            <div>
              <label for="email">CPF</label>
              <input
                type="text"
                id="cpf"
                v-model="form.cpf"
                required
                placeholder="Digite seu CPF"
                maxlength="14"
                inputmode="numeric"
              />
            </div>
              
            </div>
        </div>
        <div class="links">
          <button @click="submit()" class="button-submit">Criar</button>
        </div>
        <div class="links">
          <a href="/" class="back-link">Voltar</a>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { watch } from 'vue';
  import '../../css/home.css';
  import { useForm } from "@inertiajs/inertia-vue3";
  import { useToast } from 'vue-toastification'; 
  import axios from 'axios'; 

  const toast = useToast();

  function formatCPF(value) {
    let cpf = value.replace(/\D/g, '');
    
    if (cpf.length > 3) cpf = cpf.replace(/^(\d{3})(\d)/, '$1.$2');
    if (cpf.length > 7) cpf = cpf.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
    if (cpf.length > 11) cpf = cpf.replace(/\.(\d{3})(\d)/, '.$1-$2');
    
    return cpf.substring(0, 14);
  }

  const form = useForm({
    email: "",
    senha: "",
    cpf: "",
    nome: "",
    latitude: "",
    longitude: "",
  });

  watch(
    () => form.cpf,
    (newValue) => {
      const formatted = formatCPF(newValue || '');
      if (formatted !== newValue) {
        form.cpf = formatted;
      }
    }
  );

  function submit(){
    if (!navigator.geolocation) {
      alert('Geolocalização não é suportada pelo seu navegador');
      return;
    }

    navigator.geolocation.getCurrentPosition(
    (position) => {
      form.latitude = position.coords.latitude;
      form.longitude = position.coords.longitude;
      
      axios.post(route('store.createAccount'), form).then(response => {
          toast.success('Cadastro realizado com sucesso!');
          form.reset();
      })
      .catch(error => {
          if (error.response?.data?.errors) {
              const errors = error.response.data.errors;
              Object.values(errors).forEach(messages => {
                  messages.forEach(message => toast.error(message));
              });
          } else {
              toast.error('Erro ao processar cadastro: ' + error.message);
          }
      });
    },
    (error) => {
      alert('Erro ao obter localização. Por favor, permita o acesso à geolocalização.');
      console.error('Erro de geolocalização:', error);
    },
    {
      enableHighAccuracy: true,
      timeout: 5000,
      maximumAge: 0
    }
  );
  }
  </script>

  <style>
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
      max-width: 500px;
      text-align: center;
    }

    .preview-container {
      margin-top: 1rem;
      width: 200px;
      height: auto;
    }
    
    .forgot-password-card h2 {
      color: #1a1a1a;
      margin-bottom: 1rem;
    }
    
    .instructions {
      color: #666;
      margin-bottom: 2rem;
    }
    
    .auth-form {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }
    
    .form-group {
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
    
    .submit-btn {
      background-color: #007bff;
      color: white;
      padding: 0.8rem;
      border: none;
      border-radius: 4px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    
    .submit-btn:hover {
      background-color: #0056b3;
    }
  
    .links {
      margin-top: 1.5rem;
    }
    
    .back-link {
      color: #007bff;
      text-decoration: none;
      font-size: 0.9rem;
    }
    
    .back-link:hover {
      text-decoration: underline;
    }
    
    .success-message {
      margin-top: 1rem;
      padding: 1rem;
      background-color: #d4edda;
      color: #155724;
      border-radius: 4px;
      border: 1px solid #c3e6cb;
    }

    div label{
      text-align: left;
      margin-top: 10px;
    }
  </style>