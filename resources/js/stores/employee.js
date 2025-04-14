import { defineStore } from "pinia";
import api from "../services/api.service";

export const useEmployeeStore = defineStore("employee", {
    state: () => ({
        employees: [],
        employee: null,
        loading: false,
        error: null,
        totalRecords: 0,
        totalPages: 0,
        currentPage: 1,
        perPage: 10,
    }),

    getters: {
        getEmployeeById: (state) => (id) => {
            return state.employees.find((employee) => employee.id === id);
        },
    },

    actions: {
        async fetchEmployees(params = {}) {
            this.loading = true;

            try {
                const page = params.page || this.currentPage;
                const perPage = params.perPage || this.perPage;
                // const search = params.search || '';

                const response = await api.get("/employee", {
                    params: { page, per_page: perPage },
                });

                const employeeData = response.data.data.map((emp) => ({
                    id: emp.id,
                    joining_date: emp.joining_date ?? "—",
                    department: emp.department ?? "No Department",
                    name: emp.user?.name ?? "No User",
                    email: emp.user?.email ?? "",
                    last_login: emp.user?.last_login ?? "—",
                }));

                this.employees = employeeData;
                console.log(this.employees, "sss");
                this.totalRecords = response.data.meta?.total;
                this.totalPages = response.data.meta?.last_page;
                this.currentPage = response.data.meta?.current_page;

                return this.employees;
            } catch (error) {
                this.error =
                    error.response?.data?.message ||
                    "Failed to fetch employees";
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async fetchEmployee(id) {
            this.loading = true;

            try {
                const response = await api.get(`/employee/${id}`);
                console.log(response.data, "ddd");
                this.employee = response.data.data;
                return this.employee;
            } catch (error) {
                this.error =
                    error.response?.data?.message || "Failed to fetch employee";
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async addEmployee(employeeData) {
            this.loading = true;

            try {
                const response = await api.post("/employee", employeeData);

                return response.data;
            } catch (error) {
                this.error =
                    error.response?.data?.message ||
                    "Failed to create employee";
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateEmployee(id, employeeData) {
            this.loading = true;

            try {
                const response = await api.put(`/employee/${id}`, employeeData);

                // Update local state
                const index = this.employees.findIndex((e) => e.id === id);
                if (index !== -1) {
                    this.employees[index] = response.data;
                }

                return response.data;
            } catch (error) {
                this.error =
                    error.response?.data?.message ||
                    "Failed to update employee";
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteEmployee(id) {
            this.loading = true;

            try {
                await api.delete(`/employee/${id}`);

                // Update local state
                this.employees = this.employees.filter((e) => e.id !== id);

                return true;
            } catch (error) {
                this.error =
                    error.response?.data?.message ||
                    "Failed to delete employee";
                throw error;
            } finally {
                this.loading = false;
            }
        },
    },
});
