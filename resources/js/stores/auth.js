import { defineStore } from "pinia";
import { ref, computed } from "vue";
import api from "../services/api.service";
import ChangePassword from "../components/auth/ChangePassword.vue";

export const useAuthStore = defineStore("auth", () => {
    const user = ref(null);
    const token = ref(localStorage.getItem("token") || null);
    const userPermissions = ref([]);
    const userRoles = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const isAuthenticated = computed(() => !!token.value);

    async function login(credentials) {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.post("/auth/login", credentials);
            token.value = response.data.token;
            localStorage.setItem("token", token.value);

            await getCurrentUser();

            return response.data;
        } catch (err) {
            console.log(err.response?.data?.message);
            error.value = err.response?.data?.message || "Login failed";
            return false;
        } finally {
            loading.value = false;
        }
    }
    async function resetPassword(payload) {
        try {
            const userId = this.user?.id;
            if (!userId) throw new Error("User ID not found");

            const response = await api.post(
                `/users/reset/password/${userId}`,
                payload
            );
            return response.data;
        } catch (error) {
            throw error.response?.data?.message || "Failed to update password";
        }
    }

    async function logout() {
        try {
            if (token.value) {
                await api.post("/auth/logout");
            }
        } catch (err) {
            console.error("Logout error:", err);
        } finally {
            token.value = null;
            user.value = null;
            userPermissions.value = [];
            userRoles.value = [];
            localStorage.removeItem("token");
        }
    }

    async function getCurrentUser() {
        if (!token.value) return null;

        loading.value = true;

        try {
            const response = await api.get("/auth/current_user");
            console.log(response, "ddd");
            user.value = response.data;
            userPermissions.value = response.data.permissions || [];
            console.log(userPermissions);
            userRoles.value = response.data.roles || [];
            return user.value;
        } catch (err) {
            error.value = "Failed to fetch user data";
            token.value = null;
            localStorage.removeItem("token");
            return null;
        } finally {
            loading.value = false;
        }
    }

    function hasPermission(permission) {
        const hasCurrentPermission = userPermissions.value.some(
            (perm) => perm.name === permission
        );
        return hasCurrentPermission;
    }

    function hasRole(role) {
        const hasCurrentRole = userRoles.value.some((rol) => rol.name === role);
        return hasCurrentRole;
    }

    return {
        user,
        token,
        userPermissions,
        userRoles,
        loading,
        error,
        isAuthenticated,
        login,
        logout,
        getCurrentUser,
        hasPermission,
        resetPassword,
        hasRole,
    };
});
