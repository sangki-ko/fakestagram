<template>
    <div class="user-info-box">
        <img :src="$store.state.user.userInfo.profile" alt="">
        <div class="user-info-content">
            <h2>{{ $store.state.user.userInfo.name }}</h2>
            <h3>작성글 수 : {{ $store.state.user.userInfo.boards_count }}</h3>
        </div>
        <router-link to="/boards/create"><button class="btn btn-submit btn-bg-black">글작성</button></router-link>
    </div>
</template>

<script setup>

</script>

<style>
@import url('../../../css/userInfo.css');
</style>