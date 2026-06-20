import { createScene } from './scene.js';
import { createControls } from './controls.js';
import { createMannequin } from './mannequin.js';
import {
    loadClothingModel,
    applyTextureToModel,
    setModelColor,
    setModelScale,
} from './clothing.js';

export class ProductViewer3D {
    constructor(container, options = {}) {
        this.container = container;
        this.options = {
            autoRotate: true,
            backgroundColor: 0x0a0a0a,
            ...options,
        };

        this.sceneData = null;
        this.controls = null;
        this.model = null;
        this.mannequin = null;
        this.currentColor = null;
        this.currentScale = 1;
        this.isLoading = false;
        this.loaded = false;
    }

    init() {
        this.sceneData = createScene(this.container);
        this.controls = createControls(
            this.sceneData.camera,
            this.sceneData.renderer
        );
        this.controls.autoRotate = this.options.autoRotate;

        this.mannequin = createMannequin();
        this.sceneData.scene.add(this.mannequin);

        this.sceneData.render(() => {
            this.controls.update();
        });

        this.loaded = true;
    }

    async load(url, onProgress) {
        if (!this.loaded) this.init();
        if (!url) return;

        this.isLoading = true;

        if (this.model) {
            this.sceneData.scene.remove(this.model);
            this.disposeModel(this.model);
            this.model = null;
        }

        this.mannequin.visible = true;

        try {
            this.model = await loadClothingModel(url, onProgress);
            this.model.position.y = 0;
            this.sceneData.scene.add(this.model);
            this.isLoading = false;
            this.mannequin.visible = false;
        } catch (err) {
            this.isLoading = false;
            this.mannequin.visible = true;
            throw err;
        }
    }

    async switchTexture(textureUrl) {
        if (!this.model) return;
        try {
            await applyTextureToModel(this.model, textureUrl);
        } catch (err) {
            console.warn('Texture load failed, using color fallback', err);
        }
    }

    setColor(hexColor) {
        if (!this.model) return;
        this.currentColor = hexColor;
        setModelColor(this.model, hexColor);
    }

    setScale(scale) {
        this.currentScale = scale;
        if (this.model) {
            setModelScale(this.model, scale);
        }
    }

    autoRotate(enabled) {
        if (this.controls) {
            this.controls.autoRotate = enabled;
        }
    }

    resetCamera() {
        if (!this.sceneData || !this.controls) return;
        this.sceneData.camera.position.set(0, 1.5, 4);
        this.controls.target.set(0, 1.2, 0);
        this.controls.update();
    }

    disposeModel(obj) {
        if (!obj) return;
        obj.traverse((child) => {
            if (child.isMesh) {
                child.geometry?.dispose();
                if (child.material) {
                    const materials = Array.isArray(child.material)
                        ? child.material
                        : [child.material];
                    materials.forEach((mat) => {
                        for (const key of Object.keys(mat)) {
                            const value = mat[key];
                            if (value?.isTexture) {
                                value.dispose();
                            }
                        }
                        mat.dispose();
                    });
                }
            }
        });
    }

    destroy() {
        if (this.model) {
            this.disposeModel(this.model);
            this.model = null;
        }
        if (this.mannequin) {
            this.disposeModel(this.mannequin);
            this.mannequin = null;
        }
        if (this.controls) {
            this.controls.dispose();
            this.controls = null;
        }
        if (this.sceneData) {
            this.sceneData.destroy();
            this.sceneData = null;
        }
        this.loaded = false;
    }
}
