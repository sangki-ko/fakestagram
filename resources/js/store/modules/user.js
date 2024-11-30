import axios from "axios";
import router from '../../router';

export default {
	namespaced: true,
	state: () => ({
        authFlg: localStorage.getItem('accessToken') ? true : false,
        // JSON.parse : JSON.parse는 JavaScript에서 사용되며, JSON 문자열을 객체로 변환합니다.
        // 라라벨에서는 PHP의 json_decode 함수를 사용하여 JSON 문자열을 PHP 배열이나 객체로 변환합니다.
        // JSON 데이터는 클라이언트와 서버 간의 데이터 전송에 매우 유용합니다.
        userInfo: localStorage.getItem('userInfo') ? JSON.parse(localStorage.getItem('userInfo')) : {},
	})
	,mutations: {
        setAuthFlg(state, flg) {
            state.authFlg = flg;
        },
        
        setUserInfo(state, userInfo) {
            state.userInfo = userInfo;
        }
	}
	,actions: {
        login(context, userInfo) {
            const url = '/api/login';

            const data = JSON.stringify(userInfo);

            axios.post(url, data)
            .then(response => {
                console.log(response.data);
                localStorage.setItem('accessToken', response.data.accessToken);
                localStorage.setItem('refreshToken', response.data.refreshToken);
                localStorage.setItem('userInfo', JSON.stringify(response.data.data));
                context.commit('setAuthFlg', true);
                context.commit('setUserInfo', response.data.data);

                router.replace('/boards');
            })
            .catch(error => {
                console.error(error.response.data); // 오류 메시지 확인
                let errorMsgList = [];
                const errorData = error.response.data;

                if(error.response.status === 422) {
                    console.log(error.response.data.errors); // 각 필드의 에러 메시지 출력
                    // 유효성 체크 에러
                    if(errorData.data.account) {
                        errorMsgList.push(errorData.data.account[0]);
                    }
                    if(errorData.data.password) {
                        errorMsgList.push(errorData.data.password[0]);
                    }else if(error.response.status === 401) {
                        // 비밀번호 오류 에러
                        errorMsgList.push('비번 틀림 ㅋㅋㅋㅋㅋㅋㅋㅋ');
                    }else {
                        errorMsgList.push('나도 모르는 에러');
                    }

                    alert(errorMsgList.join('\n'));
                    
                    
                }
            });
        }
	}
	,getters: {
		
	}
}