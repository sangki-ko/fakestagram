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
                    <div>
                        <button class="btn btn-header btn-bg-black">로그인</button>
                        <button class="btn btn-header btn-bg-black">회원가입</button>
                    </div>
                    <div>
                        <button class="btn btn-header btn-bg-black">로그아웃</button>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main -->
    <main>
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

</script>

<style>
@import url('../../css/common.css');
</style>