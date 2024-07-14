// export const useGetCookies = () => {
//     var pairs = document.cookie.split(";");
//     var cookies = {} as any;
//     for (var i=0; i<pairs.length; i++){
//       var pair = pairs[i].split("=");
//       cookies[(pair[0]+'').trim()] = unescape(pair.slice(1).join('='));
//     }
//     return cookies;
// }

interface Cookie {
  [key: string]: string;
}

export const getCookies = (): { [key: string]: any } => {
  const pairs = document.cookie.split(";");
  const cookies: Cookie = {};
  for (let i = 0; i < pairs.length; i++) {
    const pair = pairs[i].split("=");
    cookies[pair[0].trim()] = decodeURIComponent(pair.slice(1).join("="));
  }
  return cookies;
};
