<template>
  <div class="auth-container">
    <div class="account-card">
      <h2>Configurações de Conta</h2>

      <div class="account-actions">
        <button class="delete-account-btn" @click="openDeleteModal">
          Excluir Conta
        </button>
      </div>

      <div class="change-password-section">
        <input
          type="password"
          v-model="form.senha"
          placeholder="Nova Senha"
        />
        <button class="change-password-btn" @click="alterarSenha">
          Altera Senha
        </button>
      </div>
    </div>

    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal">
        <p>Tem certeza que deseja excluir sua conta?</p>
        <div class="modal-actions">
          <button @click="confirmDelete">Sim</button>
          <button @click="closeDeleteModal">Não</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import '../../css/edit.css';
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import { useToast } from 'vue-toastification'; 

const form = useForm({
    senha: "",
});

const toast = useToast();

const showDeleteModal = ref(false);
const newPassword = ref("");

function openDeleteModal() {
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
}

function confirmDelete() {
  Inertia.post(route('delete.account'));
}

function alterarSenha() {
  if(form.senha == ''){
    toast.error('Preencha o campo da senha');
    return;
  }
  Inertia.post(route('changePassword.account'), form);
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

.account-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  text-align: center;
}

.account-card h2 {
  color: #1a1a1a;
  margin-bottom: 1.5rem;
}

.account-actions {
  margin-bottom: 2rem;
}

.delete-account-btn {
  background-color: #dc3545;
  color: white;
  padding: 0.8rem 1.2rem;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s;
}

.delete-account-btn:hover {
  background-color: #c82333;
}

.change-password-section {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.change-password-section input {
  flex: 1;
  padding: 0.8rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.change-password-btn {
  background-color: #28a745;
  color: white;
  padding: 0.8rem 1.2rem;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s;
}

.change-password-btn:hover {
  background-color: #218838;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  width: 90%;
  max-width: 400px;
  text-align: center;
}

.modal-actions {
  margin-top: 1.5rem;
  display: flex;
  justify-content: center;
  gap: 1rem;
}

.modal-actions button {
  padding: 0.8rem 1.2rem;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
}

.modal-actions button:first-child {
  background-color: #dc3545;
  color: white;
}

.modal-actions button:last-child {
  background-color: #6c757d;
  color: white;
}

p{
  color: black;
}
</style>
