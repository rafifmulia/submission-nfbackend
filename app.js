/**
 * Fungsi untuk menampilkan hasil download
 * @param {string} result - Nama file yang didownload
 */
 const showDownload = async (result) => {
  return new Promise((resolve, reject) => {
    setTimeout(() => {
      resolve(`Download selesai\nHasil Download: ${result}`);
    }, 3000);
  });
}

/**
 * Fungsi untuk download file
 * @param {function} callback - Function callback show
 */
const download = async () => {
  try {
    console.log(`Download dimulai`);
    const result = `windows-10.exe`;
    console.log(await showDownload(result));
  } catch (e) {
    console.error(`err: ${e}`)
  }
}

download();

/**
 * TODO:
 * - Refactor callback ke Promise atau Async Await
 * - Refactor function ke ES6 Arrow Function
 * - Refactor string ke ES6 Template Literals
 */