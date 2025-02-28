<template>
    <div class="auth-container">
      <div class="forgot-password-card">
        <h2>Recuperar Senha</h2>
        <p class="instructions">Informe seu e-mail para receber as instruções de redefinição de senha</p>
  
        <div class="auth-form">
          <div class="form-group">
            <label for="email">E-mail</label>
            <input
              type="email"
              id="email"
              v-model="form.email"
              required
              placeholder="Digite seu e-mail cadastrado"
            />
          </div>
          <button @click="recuperar()" :disabled="loading" class="submit-btn">
            <span v-if="!loading">Recuperar</span>
            <span v-else>Enviando...</span>
          </button>    

    </div>
        <div class="links">
          <a href="/" class="back-link">Voltar para o Login</a>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import '../../css/home.css';
  import { useForm } from "@inertiajs/inertia-vue3";
  import { useToast } from 'vue-toastification'; 
  
  const form = useForm({
    email: "",
  });

  const toast = useToast();

  const loading = ref(false);

  function recuperar(){
    loading.value = true; 
    axios.post(route('ForgotPasswordStore'), form).then(response => {
        toast.success('Senha enviada para o E-mail!');
        loading.value = false;
    })
    .catch(error => {
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors;
            Object.values(errors).forEach(messages => {
                messages.forEach(message => toast.error(message));
            });
        } else {
            toast.error('Erro ao processar recuperação: ' + error.message);
        }
    });
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
    max-width: 400px;
    text-align: center;
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
  </style>