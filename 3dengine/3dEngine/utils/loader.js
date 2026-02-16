export function loadModel(scene, camera, controls, { folderPath,fileName, desiredSize = 1.0 }) {
  return new Promise((resolve, reject) => {
    fetch(folderPath + fileName)
       .then(res => res.text())
      .then(objText => {
        const mtlMatch = objText.match(/^mtllib\s+(.+)$/m);
        if (!mtlMatch) throw new Error('No mtllib found in OBJ file');
        const mtlFile = mtlMatch[1].trim();

        const mtlLoader = new THREE.MTLLoader();
        mtlLoader.setPath(folderPath);
        mtlLoader.load(mtlFile, materials => {
          materials.preload();

          // ========== ENHANCED MATERIAL & TEXTURE SETTINGS ==========
          for (let name in materials.materials) {
            const mat = materials.materials[name];
            
            // Texture quality improvements
            if (mat.map) {
              mat.map.encoding = THREE.sRGBEncoding;
              mat.map.anisotropy = 16; // Maximum anisotropic filtering
              mat.map.minFilter = THREE.LinearMipmapLinearFilter;
              mat.map.magFilter = THREE.LinearFilter;
              mat.map.generateMipmaps = true;
            }

            // Apply to all texture maps if they exist
            const textureTypes = ['map', 'normalMap', 'bumpMap', 'specularMap', 'roughnessMap', 'metalnessMap'];
            textureTypes.forEach(texType => {
              if (mat[texType]) {
                mat[texType].anisotropy = 16;
                mat[texType].encoding = THREE.sRGBEncoding;
                mat[texType].minFilter = THREE.LinearMipmapLinearFilter;
                mat[texType].magFilter = THREE.LinearFilter;
              }
            });

            // Enhanced material properties for realism
            mat.side = THREE.FrontSide; // Better performance than DoubleSide
            
            // Add subtle roughness and metalness for realistic look
            if (mat.roughness === undefined) mat.roughness = 0.7;
            if (mat.metalness === undefined) mat.metalness = 0.1;
            
            // Enable better shading
            mat.flatShading = false;
          }

          const objLoader = new THREE.OBJLoader();
          objLoader.setMaterials(materials);
          objLoader.setPath(folderPath);
          objLoader.load(fileName, object => {
            const modelGroup = new THREE.Group();
            modelGroup.add(object);
            modelGroup.userData.isRootModel = true;

            fitObjectToScene(modelGroup, camera, controls, desiredSize);

            modelGroup.traverse(child => {
              if (child.isMesh) {
                // ========== PER-MESH ENHANCEMENTS ==========
                
                // Enable shadow casting and receiving
                child.castShadow = true;
                child.receiveShadow = true;
                
                // Improve geometry normals for smooth shading
                if (child.geometry) {
                  child.geometry.computeVertexNormals();
                  
                  // Optional: Compute tangents for normal mapping
                  if (child.geometry.attributes.uv) {
                    child.geometry.computeTangents();
                  }
                }
                // Material enhancements
                if (child.material) {
                  // Convert to MeshStandardMaterial for better realism (if using basic material)
                  const materialName = child.material.name;
                  if (child.material.type === 'MeshPhongMaterial' || child.material.type === 'MeshBasicMaterial') {
                    const oldMat = child.material;
                    child.material = new THREE.MeshStandardMaterial({
                      name: materialName, 
                      map: oldMat.map,
                      color: oldMat.color || 0xffffff,
                      roughness: 0.7,
                      metalness: 0.1,
                      side: THREE.FrontSide
                    });
                  }
                  
                  // Fallback color if no texture
                  if (!child.material.map) {
                    child.material.color.set(0xcccccc);
                  }
                  
                  child.material.needsUpdate = true;
                }
              }
            });

            scene.add(modelGroup);
            document.getElementById('loading').style.display = 'none';
            console.log('✅ Model loaded with high quality settings!');
            resolve(modelGroup);
          }, undefined, err => {
            console.error('OBJ load error:', err);
            document.getElementById('loading').textContent = 'Error loading model';
            reject(err);
          });
        }, undefined, err => {
          console.error('MTL load error:', err);
          document.getElementById('loading').textContent = 'Error loading materials';
          reject(err);
        });
      })
      .catch(err => {
        console.error('Failed to parse OBJ:', err);
        document.getElementById('loading').textContent = 'Error loading OBJ file';
        reject(err);
      });
  });
}

function fitObjectToScene(object, camera, controls, desiredSize = 1.0) {
  const box = new THREE.Box3().setFromObject(object);
  const size = box.getSize(new THREE.Vector3());
  const center = box.getCenter(new THREE.Vector3());

  const maxDim = Math.max(size.x, size.y, size.z);
  const scale = desiredSize / maxDim;
  object.scale.setScalar(scale);

  box.setFromObject(object);
  box.getCenter(center);
  object.position.sub(center);

  const fitOffset = 1.2;
  const sphere = box.getBoundingSphere(new THREE.Sphere());
  const distance = fitOffset * sphere.radius / Math.tan(THREE.MathUtils.degToRad(camera.fov * 0.5));
  camera.position.set(distance, distance, distance);
  camera.lookAt(0, 0, 0);
  controls.target.set(0, 0, 0);
  controls.update();
}