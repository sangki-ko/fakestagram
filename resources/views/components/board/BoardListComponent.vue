<template>
    <!-- 리스트 출력 -->
    <div class="board-list-box">
        <div v-for="item in boardList" :key="item" @click="openModal(item.board_id)" class="item">
            <img :src="item.img" alt="">
        </div>
    </div>
    
    <!-- 상세 모달 출력 -->
    <div v-if="modalFlg" class="board-detail-box">
        <div v-if="lodingFlg"class="item lodingFlg" >로딩중</div>
        <div v-else class="item">
            <img :src="boardDetail.img" alt="">
            <hr>
            <p>{{ boardDetail.content }}</p>
            <hr>
            <div class="etc-box">
                <div>
                    <span>작성자 : {{ boardDetail.user.name }}</span>
                </div>
                <div>
                    <button @click="deleteList(boardDetail.board_id)">삭제</button>
                    <button @click="closeModal">닫기</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onBeforeMount, ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

const boardList = computed(() => store.state.board.boardList);

// 비포 마운트 처리
onBeforeMount(() => {
    if(store.state.board.boardList.length < 1) {
        store.dispatch('board/boardListPagenation');
    }
});


// ----------------------
// 스크롤 이벤트 관련
const boardScrollEvent = () => {
    // console.log('--------- 스크롤 이벤트 시작 ---------');
    if(store.state.board.controllFlg) {
        const docHeight = document.documentElement.scrollHeight; // 문서 기준 Y축 길이
        const winHeight = window.innerHeight; // 윈도우 기준 Y축 길이
        const nowHeight = window.scrollY; // 현재 스크롤 위치
        const viewHeight = docHeight - winHeight; // 끝까지 스크롤 했을 때의 Y축 위치

        // console.log(viewHeight, nowHeight)
        if(viewHeight <= nowHeight) {
            store.dispatch('board/boardListPagenation');
        }

    }


    // console.log('문서 Y축 :' + docHeight);
    // console.log('윈도우 Y축 :' + winHeight);
    // console.log('현재 Y축 :' + nowHeight);
    // console.log('최대 스크롤 Y축 :' + viewHeight);
    // console.log('--------- 스크롤 이벤트 시작 ---------');
};

window.addEventListener('scroll', boardScrollEvent);

const lodingFlg = computed(() => store.state.board.LodingFlg);
// ------------
// 모달 관련
const modalFlg = ref(false);

const openModal = (id) => {
    store.dispatch('board/showBoard', id);
    modalFlg.value = true;
    
}
const boardDetail = computed(() => store.state.board.boardDetail);
const closeModal = () => {
    modalFlg.value = false;
}

const deleteList = (id) => {
    store.dispatch('board/deleteList', id);
    modalFlg.value = false;
}


</script>

<style>
@import url('../../../css/boardList.css');
</style>