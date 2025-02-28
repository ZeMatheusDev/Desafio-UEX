<template>
    <div id="main" :class="{ 'expanded': isFocused }">
        <p>Login</p> 

        <div id="form"> 
            <div>
                <span class="p-float-label"> 
                    <InputText type="text" v-model="form.email" placeholder="Email" @focus="isFocused = true" @blur="isFocused = false"/>
                </span>
            </div>
            <div class="div-form">
                <span class="p-float-label"> 
                    <InputText type="password" v-model="form.senha" placeholder="Senha" @focus="isFocused = true" @blur="isFocused = false"/>
                </span>
            </div>
            <div>
                <button class="button-uex" @click="login()">Entrar</button>
            </div>
            <div>
                <button id="create" @click="createAccount()">Criar Conta</button>
            </div>
            <div>
                <button id="forget" @click="forgetPassword()">Esqueceu a senha?</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import InputText from "primevue/inputtext";
import { Inertia } from "@inertiajs/inertia";
import '../../css/home.css'; 
import { useForm } from "@inertiajs/inertia-vue3";
import { useToast } from 'vue-toastification'; 
import axios from 'axios'; 

const isFocused = ref(false);

const toast = useToast();

const form = useForm({
    email: "",
    senha: "",
});

const props = defineProps({
  mensagem: String,
});

function createAccount(){
    Inertia.get(route('createAccount'));
}

function login(){
    axios.post(route('login'), form).then(response => {
        toast.success('Login realizado com sucesso!');
        setTimeout(() => {
            window.location.reload();        
        }, 1000);
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
}

onMounted(() => {
  if (props.mensagem != '') {
  }
});

function forgetPassword(){
    Inertia.get(route('forgotPassword'));
}
</script>

<style>
.div-form{
    margin-top: 30px !important;
}

#form{
    text-align: center;
    padding-top: 100px;
}

#forget{
    font-size: 12px;
    color: rgb(255, 255, 255);
}

#forget:hover{
    text-decoration: underline;
}

#create:hover{
    color: rgb(168, 168, 168);
}

button{
    margin-top: 30px;
}
</style>
