type MethodType = "GET" | "POST" | "PUT" | "DELETE" | "OPTIONS";

export const useUseSanctumFetch = async (
  path: string,
  method: MethodType = "GET",
  body?: object
) => {
  const config = useRuntimeConfig();
  // const { token } = useMyAuthStore();
  let csrfToken = useCookie("XSRF-TOKEN").value as string;

  if (!csrfToken) {
    await useFetch(`${config.public.API_URL}/sanctum/csrf-cookie`, {
      credentials: "include",
      watch: false,
    });

    csrfToken = useCookie("XSRF-TOKEN").value as string;
  }

  let header = [] as any;

  if (method != "GET") {
    header["X-XSRF-TOKEN"] = csrfToken;
  }

  if (body) {
    header["Accept"] = "application/json";
    header["Content-Type"] = "application/json";
  }

  if (path.startsWith("/api")) {
    header["Authorization"] = `Bearer ${useCookie("token").value}`;
  }

  if (import.meta.client) {
    const { data, error } = await useFetch<any>(config.public.API_URL + path, {
      method: method,
      body: body,
      headers: {
        ...header,
        // "X-XSRF-TOKEN": csrfToken,
      },
      credentials: "include",
      watch: false,
    });

    return { data, error };
  }
};
