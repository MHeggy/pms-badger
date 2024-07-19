// Initialize Three.js scene
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer();
renderer.setSize(window.innerWidth, window.innerHeight);
document.getElementById('container').appendChild(renderer.domElement);

// Create a folder object
function createFolder() {
    const geometry = new THREE.BoxGeometry();
    const material = new THREE.MeshBasicMaterial({ color: 0xff0000 });
    const folder = new THREE.Mesh(geometry, material);
    return folder;
}

// Create a file object
function createFile() {
    const geometry = new THREE.BoxGeometry();
    const material = new THREE.MeshBasicMaterial({ color: 0x0000ff });
    const file = new THREE.Mesh(geometry, material);
    return file;
}

// Create folder and file hierarchy
const rootFolder = createFolder();
rootFolder.position.set(0, 0, 0);
scene.add(rootFolder);

const subFolder1 = createFolder();
subFolder1.position.set(-2, 0, 0);
rootFolder.add(subFolder1);

const subFolder2 = createFolder();
subFolder2.position.set(2, 0, 0);
rootFolder.add(subFolder2);

const file1 = createFile();
file1.position.set(-2, 1, 0);
scene.add(file1);

const file2 = createFile();
file2.position.set(2, 1, 0);
scene.add(file2);

// Position the camera
camera.position.z = 5;

// Render loop
const animate = function () {
    requestAnimationFrame(animate);
    renderer.render(scene, camera);
};
animate();
