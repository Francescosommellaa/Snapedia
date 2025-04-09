/**
 * Restituisce la URL di un'immagine da Wikipedia con una larghezza personalizzata.
 * Funziona solo con URL contenenti "/thumb/".
 */
export function setImageWidth(thumbnailUrl: string, width: number): string {
  if (!thumbnailUrl.includes("/thumb/")) return thumbnailUrl;
  return thumbnailUrl.replace(/\/\d+px-/, `/${width}px-`);
}