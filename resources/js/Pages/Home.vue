<template>
    <div id="main" :class="{ 'expanded': isFocused }">
        <p>Login</p> 

        <div id="form"> 
            <div>
                <span class="p-float-label"> 
                    <InputText type="text" placeholder="Login/Email" @focus="isFocused = true" @blur="isFocused = false"/>
                </span>
            </div>
            <div class="div-form">
                <span class="p-float-label"> 
                    <InputText type="password" placeholder="Senha" @focus="isFocused = true" @blur="isFocused = false"/>
                </span>
            </div>
            <div>
                <button class="button-uex">Entrar</button>
            </div>
            <div>
                <button @click="createAccount()">Criar Conta</button>
            </div>
            <div>
                <button @click="forgetPassword()">Esqueci a Senha</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import InputText from "primevue/inputtext";

const isFocused = ref(false);

function createAccount(){
    
}

function forgetPassword(){
    
}
</script>

<style>
body {
    background-image: url('/public/background.png');
    color: white;
}

#main p{
    padding: 40px;
    font-size: 20px;
    text-align: center;
}

#main {
    background-color: rgb(35, 50, 57);
    width: 350px;
    height: 100vh;
    transition: 0.4s ease;
}

button{
    margin-top: 30px;
}

#main.expanded {
    width: 400px;
}

.div-form{
    margin-top: 30px !important;
}

#form{
    text-align: center;
    padding-top: 100px;
}
</style>