export default defineEventHandler(async (event) => {
  const { token } = await readBody(event);
  setCookie(event, "token", token, {
    httpOnly: true,
    sameSite: "lax",
    // expires: new Date(Date.now() + 60 * 60 * 1000 * 24), // 1 days
    maxAge: 60,
  });

  return {
    message: "Cookie set successfully",
  };
});
