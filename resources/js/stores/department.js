import { defineStore } from "pinia";
import api from "../services/api.service";

export const useDepartmentStore = defineStore("department", {
    state: () => ({
        departments: [],
        loading: false,
        currentDepartment: null,
        error: null,
        totalRecords: 0,
        totalPages: 0,
        currentPage: 1,
        perPage: 10,
    }),
    actions: {
        async fetchDepartments(params = {}) {
            console.log(params);
            this.loading = true;
            try {
                let apiParams = {};

                if (params.all) {
                    apiParams.all = true;
                } else {
                    apiParams.page = params.page || 1;
                    apiParams.per_page = params.perPage || 25;
                }

                const response = await api.get("/department", {
                    params: apiParams,
                });

                // console.log(response.data.meta.total, 'ehe')
                // Only store the actual department list
                this.departments = response.data.data;
                this.totalRecords = response.data.meta?.total || 0;
                this.totalPages = response.data.meta?.last_page || 1;
                this.currentPage = response.data.meta?.current_page || 1;

                return this.departments;
            } catch (error) {
                this.error =
                    error.response?.data?.message ||
                    "Failed to fetch departments";
                throw error;
            } finally {
                this.loading = false;
            }
        },
        async fetchDepartmentById(id) {
            this.loading = true;
            try {
                const response = await api.get(`/department/${id}`);
                this.currentDepartment = response.data;
                return response.data;
            } catch (error) {
                this.error =
                    error.response?.data?.message ||
                    "Failed to fetch department";
                throw error;
            } finally {
                this.loading = false;
            }
        },
        async addDepartment(department) {
            try {
                const response = await api.post("/department", department);
                this.departments.push(response.data);
                return response.data;
            } catch (error) {
                throw error;
            }
        },
        async updateDepartment(id, department) {
            try {
                const response = await api.put(`/department/${id}`, department);
                const index = this.departments.findIndex((d) => d.id === id);
                if (index !== -1) {
                    this.departments.splice(index, 1, response.data);
                }
                return response.data;
            } catch (error) {
                throw error;
            }
        },
        async deleteDepartment(id) {
            try {
                await api.delete(`/department/${id}`);
                this.departments = this.departments.filter((d) => d.id !== id);
            } catch (error) {
                throw error;
            }
        },
    },
});
