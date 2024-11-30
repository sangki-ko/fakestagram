<!-- 템플릿 !! -->
<template>
    <div class="form-box">
        <p class="form-title">로그인</p>
        <input v-model="userInfo.account" type="text" name="account" placeholder="아이디를 입력해주세요.">
        <input v-model="userInfo.password" type="password" name="password" placeholder="비밀번호를 입력해주세요.">
        <button @click="$store.dispatch('user/login', userInfo)" class="btn btn-submit btn-bg-black">로그인</button>
    </div>
</template>

<!-- 스크립트 !! -->
<script setup>

import { reactive } from 'vue';


    const userInfo = reactive({
        account: '',
        password: '',
    });

</script>

<!-- CSS !! -->
<style>
    
</style>