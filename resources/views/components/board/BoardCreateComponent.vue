<template>
    <div class="form-box">
        <h3 class="form-title">글 작성</h3>
        <img :src="preview">
        <textarea v-model="boardInfo.content" name="content" placeholder="내용을 입력해주세요." maxlength="200"></textarea>
        <!-- accept : 사용자가 파일 선택 창을 열면, 이미지 파일(예: JPG, PNG, GIF 등)만 선택할 수 있습니다. -->
        <input @change="setFile" type="file" name="file" accept="image/*">
        <button @click="$store.dispatch('board/storeBoard', boardInfo)" class="btn-bg-black btn-submit">작성</button>
        <button @click="$router.push('/boards')" class="btn-bg-black btn-submit">취소</button>
    </div>
</template>

<script setup>

import { reactive, ref } from 'vue';

    // 디바운싱 : 특정 처리가 끝나기 전까지 재요구를 할 수는 없다.
    const boardInfo = reactive({
        content: '',
        file: null,
    });

    const preview = ref('');

    const setFile = (e) => {
        boardInfo.file = e.target.files[0];
        preview.value = URL.createObjectURL(boardInfo.file);
    }
</script>

<style>
@import url(../../../css/boardList.css);
</style>