import { getAccessToken } from '../Step 2 SSO Implementation/Integrate SSO provider with your website/AuthenticationClient';

class ApiService {
    constructor() {
        this.baseUrl = process.env.REACT_APP_API_BASE_URL;
        this.cache = new Map();
    }

    async request(endpoint, options = {}) {
        const url = `${this.baseUrl}${endpoint}`;
        const cacheKey = JSON.stringify({ url, options });

        // Check cache first
        if (this.cache.has(cacheKey)) {
            return this.cache.get(cacheKey);
        }

        try {
            const token = await getAccessToken();
            const response = await fetch(url, {
                ...options,
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    ...options.headers
                }
            });

            if (!response.ok) {
                throw new Error(`API request failed: ${response.statusText}`);
            }

            const data = await response.json();
            
            // Cache the response
            this.cache.set(cacheKey, data);
            
            return data;
        } catch (error) {
            console.error('API request error:', error);
            throw error;
        }
    }

    async getUserData() {
        return this.request('/user/data');
    }

    async getLocationData() {
        return this.request('/location');
    }

    async getNearbyRestaurants(lat, lon) {
        return this.request(`/restaurants/nearby?lat=${lat}&lon=${lon}`);
    }

    async getRecommendedProducts(interests) {
        return this.request('/products/recommended', {
            method: 'POST',
            body: JSON.stringify({ interests })
        });
    }
}

export default new ApiService();
