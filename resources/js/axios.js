import axios from 'axios';

const axiosInstance = axios.create({
    // 우리가 요청을 주고 받는 데에 있어서 어떠한 데이터 타입이다 라고 명시해주는 기본 설정
    headers: {
        'Content-Type': 'application/json',
    }
});

export default axiosInstance;