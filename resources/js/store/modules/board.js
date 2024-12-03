import { forEach, slice } from "lodash";
import axios from "../../axios";
import router from "../../router";

export default {
	namespaced: true,
	state: () =>({
        boardList: [],
        page: 0,
        boardDetail: null,
        controllFlg: true,
        lastPageFlg: false,
        LodingFlg: false,
	})
	,mutations: {
        setBoardList(state, boardList) {
            state.boardList = state.boardList.concat(boardList);
        },
        setPage(state, page) {
            state.page = page;
        },
        setControllFlg(state, flg) {
            state.controllFlg = flg;
        },
        setLastPageFlg(state, flg) {
            state.lastPageFlg = flg;
        },
        setBoardListUnshift(state, board) {
            state.boardList.unshift(board);
        },
        setBoardDetail(state, board) {
            state.boardDetail = board;
        },
        setLodingFlg(state, flg) {
            state.LodingFlg = flg;
        },
        deleteBoardList(state, boardPk) {
            state.boardList.forEach((item, key) => {
                if(item.board_id === boardPk) {
                    // splice : 첫 번째 파라미터(시작하는 index), 두 번째 파라미터는 (자를 인덱스 갯수);
                    state.boardList.splice(key, 1);
                    return false;
                }
            });
        }
	}
	,actions: {
        // 게시글 획득 (페이지네이션도 같이)
        boardListPagenation(context) {
            // 디바운싱 처리 시작
            if(context.state.controllFlg && !context.state.lastPageFlg) {
                context.commit('setControllFlg', false);

                const url = '/api/boards?page=' + context.getters['getNextPage'];
                const config = {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('accessToken'),
                    }
                }


                axios.get(url, config)
                .then(response => {
                    context.commit('setBoardList', response.data.boardList.data);
                    context.commit('setPage', response.data.boardList.current_page);    

                    if(response.data.boardList.current_page >= response.data.boardList.last_page) {
                        context.commit('setLastPageFlg', true);
                    }
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    context.commit('setControllFlg', true);
                });
        }
    },

        showBoard(context, id) {
            context.commit('setLodingFlg', true);
            const url = '/api/boards/' + id;
            const config = {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('accessToken'),
                }
            }

            axios.get(url, config)
            .then(response => {
                context.commit('board/setBoardDetail', response.data.board, {root: true});
                
                context.commit('setLodingFlg', false);
            })
            .catch(error => {
                console.error(error);
            });
        },

        storeBoard(context, data) {
            if(context.state.controllFlg) {
                context.commit('setControllFlg', false);
                const url = '/api/boards';
                const config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': 'Bearer ' + localStorage.getItem('accessToken'),
                    }
                }

                const formData = new FormData;

                formData.append('content', data.content);
                formData.append('file', data.file);

                axios.post(url, formData, config) 
                .then(response => {
                    context.commit('setBoardListUnshift', response.data.board);

                    context.commit('user/setUserInfoBoardsCount', null, {root: true});

                
                    router.replace('/boards'); 
                })
                .catch(error => {
                    console.error(error);
                    alert(error(error));
                })
                .finally(() => {
                    context.commit('setControllFlg', true);
                });
            }
        },

        deleteList(context, id) {
            const url = '/api/boards/' + id;
            const config = {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('accessToken'),
                }
            }

            axios.post(url, config)
            .then(response => {
                alert('삭제 완료');

                context.commit('deleteBoardList', id);
            })
            .catch(error => {
                console.error(error);
            });
        }


}
	,getters: {
		getNextPage(state) {
            return state.page + 1;
        }
	}
}
