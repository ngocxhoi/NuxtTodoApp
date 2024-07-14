import { defineStore } from "pinia";

export const useMyGlobalStore = defineStore({
  id: "myGlobalStore",
  state: () => ({
    projects: [] as any,
    errorBag: {} as any,
    selectedProject: {} as any,
    nameTitle: "",
  }),
  actions: {
    resetErrorBag() {
      this.errorBag = {} as any;
    },

    transformValidationErrors(error: any) {
      if (error) {
        for (let key in error) {
          this.errorBag[key] = error[key]; // assuming error messages are always in first position in array
        }
      }
    },

    deleteProject(id: string) {
      this.projects.forEach((el: any) => {
        if (el.id === id) {
          this.projects.splice(this.projects.indexOf(el), 1);
          return;
        }
      });
    },
  },
  persist: true,
});
