document.addEventListener('DOMContentLoaded', function() {
    // Variables para manejar el estado
    let lastUploadedImage = null;
    let currentTemplate = 'pokemoncard1';
    const preview = document.getElementById('preview-image');
    const templateIcon = document.getElementById('template-icon');
    const templateSelect = document.getElementById('template');

    // Mapeo de plantillas a sus respectivos tipos de energía (asegúrate que coincida con tus nombres de archivo)
    const templateEnergyMap = {
        'pokemoncard1': 'lightning',
        'pokemoncard2': 'grass',
        'pokemoncard3': 'fire',
        'pokemoncard4': 'psychic',
        'pokemoncard5': 'water'
    };

    // Función para cargar una imagen y devolver una Promise
    function loadImage(src) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = () => {
                console.error('Error al cargar la imagen:', src);
                reject(new Error(`No se pudo cargar la imagen: ${src}`));
            };
            img.src = src;
        });
    }

    // Función para actualizar el icono de plantilla
    function updateTemplateIcon() {
        if (!templateSelect || !templateIcon) return;
        
        const selectedTemplate = templateSelect.value;
        const energyType = templateEnergyMap[selectedTemplate];
        
        if (energyType) {
            // Añadir timestamp para evitar caché
            const timestamp = new Date().getTime();
            templateIcon.src = `/images/energy/${energyType}.png?t=${timestamp}`;
            console.log('Actualizando icono a:', templateIcon.src); // Para depuración
        }
    }

    // Función para actualizar la vista previa con todos los elementos
    async function updatePreview() {
        if (!preview) return;

        // Actualizar el icono del selector primero
        updateTemplateIcon();

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        // Tamaño estándar para la vista previa
        canvas.width = 350;
        canvas.height = 488;

        // 1. Rellenar con el color de fondo
        const bgColor = document.getElementById('bg_color')?.value || '#ffffff';
        ctx.fillStyle = bgColor;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // 2. Dibujar la imagen del Pokémon si existe
        if (lastUploadedImage) {
            // Área de la imagen en la vista previa
            const imgAreaX = 30;
            const imgAreaY = 70;
            const imgAreaWidth = 290;
            const imgAreaHeight = 200;

            // Calcular dimensiones manteniendo relación de aspecto
            const ratio = lastUploadedImage.width / lastUploadedImage.height;
            let drawWidth, drawHeight, offsetX = 0, offsetY = 0;

            if (ratio > (imgAreaWidth / imgAreaHeight)) {
                drawWidth = imgAreaWidth;
                drawHeight = imgAreaWidth / ratio;
                offsetY = (imgAreaHeight - drawHeight) / 2;
            } else {
                drawHeight = imgAreaHeight;
                drawWidth = imgAreaHeight * ratio;
                offsetX = (imgAreaWidth - drawWidth) / 2;
            }

            ctx.drawImage(
                lastUploadedImage,
                imgAreaX + offsetX,
                imgAreaY + offsetY,
                drawWidth,
                drawHeight
            );
        }

        // 3. Dibujar debilidad si existe
        const weaknessType = document.getElementById('weakness_type')?.value;
        const weaknessAmount = document.getElementById('weakness_amount')?.value;
        if (weaknessType && weaknessAmount) {
            try {
                const weaknessIcon = await loadImage(`/images/energy/${weaknessType}.png`);
                ctx.drawImage(weaknessIcon, 30, 420, 25, 25);
                ctx.fillStyle = '#000';
                ctx.font = '16px Arial';
                ctx.fillText(`-${weaknessAmount}`, 60, 440);
            } catch (e) {
                console.error('Error loading weakness icon:', e);
            }
        }

        // 4. Dibujar resistencia si existe
        const resistanceType = document.getElementById('resistance_type')?.value;
        const resistanceAmount = document.getElementById('resistance_amount')?.value;
        if (resistanceType && resistanceAmount) {
            try {
                const resistanceIcon = await loadImage(`/images/energy/${resistanceType}.png`);
                ctx.drawImage(resistanceIcon, 100, 420, 25, 25);
                ctx.fillStyle = '#000';
                ctx.font = '16px Arial';
                ctx.fillText(`-${resistanceAmount}`, 130, 440);
            } catch (e) {
                console.error('Error loading resistance icon:', e);
            }
        }

        // 5. Dibujar la plantilla encima
        try {
            const templateImg = await loadImage(`/images/templates/${currentTemplate}.png`);
            ctx.drawImage(templateImg, 0, 0, canvas.width, canvas.height);
            preview.src = canvas.toDataURL();
        } catch (e) {
            console.error('Error loading template image:', e);
        }
    }

    // Maneja el cambio de plantilla
    if (templateSelect) {
        templateSelect.addEventListener('change', function() {
            currentTemplate = this.value;
            console.log('Plantilla cambiada a:', currentTemplate); // Para depuración
            updatePreview();
        });
    }

    // Maneja el cambio de imagen subida
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    lastUploadedImage = new Image();
                    lastUploadedImage.src = event.target.result;
                    lastUploadedImage.onload = function() {
                        updatePreview();
                    };
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }

    // Maneja el cambio de color
    const bgColorInput = document.getElementById('bg_color');
    if (bgColorInput) {
        bgColorInput.addEventListener('input', updatePreview);
    }

    // Maneja cambios en debilidad/resistencia
    const weaknessTypeInput = document.getElementById('weakness_type');
    const weaknessAmountInput = document.getElementById('weakness_amount');
    const resistanceTypeInput = document.getElementById('resistance_type');
    const resistanceAmountInput = document.getElementById('resistance_amount');

    if (weaknessTypeInput) weaknessTypeInput.addEventListener('change', updatePreview);
    if (weaknessAmountInput) weaknessAmountInput.addEventListener('input', updatePreview);
    if (resistanceTypeInput) resistanceTypeInput.addEventListener('change', updatePreview);
    if (resistanceAmountInput) resistanceAmountInput.addEventListener('input', updatePreview);

    // Maneja el reset del formulario
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('reset', function() {
            // Restablecer valores por defecto
            currentTemplate = 'pokemoncard1';
            lastUploadedImage = null;

            // Esperar un momento para que el reset complete
            setTimeout(() => {
                if (preview) {
                    preview.src = '/images/templates/pokemoncard1.png';
                }
                updateTemplateIcon();
                console.log('Formulario reiniciado'); // Para depuración
            }, 50);
        });
    }

    // Inicializar vista previa
    updatePreview();
    console.log('Script de vista previa cargado'); // Para depuración
});