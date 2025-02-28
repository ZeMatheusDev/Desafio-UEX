<template>
    <div id="dashboard-container">
      <div id="main">
        <button @click="editAccount()">⚙️</button>
        <h1>Contatos</h1> 
        <div id="search-container">
          <input 
            type="text" 
            v-model="searchTerm" 
            placeholder="Pesquisar por nome ou CPF..."
            class="search-input"
          >
        </div>
        <div id="form">
          <div v-for="contato in paginatedContatos" :key="contato.id" class="contact-item">
            <div id="contato" @click="handleContactClick(contato)">
                <div class="contact-info">
                <span class="location-icon">📍</span>
                <span class="contact-name">{{ contato.nome }} - {{ contato.cpf }}</span>
            </div>
          </div>
            <div>
              <button class="delete-btn" @click="openDeleteModal(contato.id)">🗑️</button>
              <button class="edit-btn" @click="editar(contato.token)">✏️</button>
            </div>
          </div>
        </div>
        <div class="pagination">
          <button class="page" @click="currentPage--" :disabled="currentPage === 1">Anterior</button>
          <span>Página {{ currentPage }} de {{ totalPages }}</span>
          <button class="page"  @click="currentPage++" :disabled="currentPage === totalPages">Próxima</button>
        </div>
        <div class="action-buttons">
          <button class="create" @click="cadastroContato()">➕ Novo contato</button>
          <button class="logout" @click="logout()">🚪 Logout</button>
        </div>
      </div>
  
      <div id="map-container" v-if="selectedMapUrl">
        <img :src="selectedMapUrl" alt="Mapa do contato" />
      </div>
  
      <div v-if="showModal" class="modal-overlay">
        <div class="modal">
          <h3>Confirmar Exclusão</h3>
          <p>Tem certeza que deseja excluir este contato?</p>
          <div class="modal-actions">
            <button @click="confirmDelete" class="confirm-btn">Sim</button>
            <button @click="cancelDelete" class="cancel-btn">Não</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  

<script setup>
import '../../css/dashboard.css'; 
import { Inertia } from "@inertiajs/inertia";
import { ref, computed, watch } from 'vue';

const props = defineProps({
  contatos: Array,
  maps: String,
});

const showModal = ref(false);
const selectedContactId = ref(null);
const searchTerm = ref('');
const currentPage = ref(1);
const itemsPerPage = 10;

const filteredContatos = computed(() => {
    if (!searchTerm.value) {
        return props.contatos;
    }
    
    const term = searchTerm.value.toLowerCase().trim();
    
    return props.contatos.filter(contato => {
        const nome = contato.nome?.toLowerCase() || '';
        const cpf = contato.cpf?.toLowerCase() || '';
        return nome.includes(term) || cpf.includes(term);
    });
});

const selectedMapUrl = ref(props.maps.original.map_url);

const paginatedContatos = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredContatos.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredContatos.value.length / itemsPerPage);
});

watch(searchTerm, () => {
    currentPage.value = 1;
});

function openDeleteModal(id) {
    selectedContactId.value = id;
    showModal.value = true;
}

async function handleContactClick(contato) {  
    axios.get(`/api/contact-map/${contato.id}`).then(function(response){
        selectedMapUrl.value = response.data.original.map_url;        
    });
}

function cancelDelete() {
    selectedContactId.value = null;
    showModal.value = false;
}

function confirmDelete(){
    Inertia.post(route('delete.contact'), {id: selectedContactId.value});
}   

function logout(){
    Inertia.post(route('logout'));
}

function cadastroContato(){
    Inertia.get(route('createContact'));
}

function editar(token){
    Inertia.get(route('edit.contact', {token: token}))
}

function editAccount(){
    Inertia.get(route('edit.account'))
}
</script>

<style>
#search-container {
    margin: 20px 0;
    padding: 0 15px;
}

.search-input {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ccc;
    border-radius: 25px;
    font-size: 16px;
    outline: none;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 90%;
}

.modal h3 {
    color: #dc3545;
    margin-bottom: 1rem;
}

.modal p {
    margin-bottom: 1.5rem;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.confirm-btn {
    background: #dc3545;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.cancel-btn {
    background: #6c757d;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

#map-container{
    margin: 200px;
}

.page{
    padding: 5px;
}

#contato{
    cursor: pointer;
}
</style>