<template>
    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="header-content">
                <div class="title">
                    <a href=""><h1>Vuestagram</h1></a>
                </div>
                <img src="/logo.png" alt="" class="img-logo">
                <div class="btn-group">
                    <div v-if="!$store.state.user.authFlg">
                        <button class="btn btn-header btn-bg-black">로그인</button>
                        <button class="btn btn-header btn-bg-black">회원가입</button>
                    </div>
                    <div v-if="$store.state.user.authFlg">
                        <button @click="$store.dispatch('user/logout')" class="btn btn-header btn-bg-black">로그아웃</button>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main -->
    <main>
        <UserInfoComponent v-if="$store.state.user.authFlg" />
        <div class="container"> 
            <router-view>
            </router-view>
        </div>
    </main>
    <!-- Footer -->
    <footer>
        <p>ⓒ 2024. Choi Sang Ki All rights reserved.</p>
    </footer>
</template>

<script setup>
    import UserInfoComponent from './user/UserInfoComponent';
</script>

<style>
@import url('../../css/common.css');
</style>