import Dropzone from 'dropzone';

// Disable auto discover for all elements
Dropzone.autoDiscover = false;

// Initialize Dropzone instances
document.addEventListener('DOMContentLoaded', function() {
    // Photos Dropzone
    if (document.getElementById('photos-dropzone')) {
        const photosDropzone = new Dropzone('#photos-dropzone', {
            url: '#', // We'll handle upload on form submit
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            dictDefaultMessage: 'Glissez-déposez vos photos ici ou cliquez pour sélectionner',
            dictRemoveFile: 'Supprimer',
            dictCancelUpload: 'Annuler',
            dictUploadCanceled: 'Téléchargement annulé',
            dictInvalidFileType: 'Type de fichier non autorisé',
            dictFileTooBig: 'Fichier trop volumineux ({{filesize}}MB). Taille max: {{maxFilesize}}MB',
            dictMaxFilesExceeded: 'Vous ne pouvez pas télécharger plus de {{maxFiles}} fichiers',
            maxFilesize: 5, // MB
        });

        window.photosDropzone = photosDropzone;
    }

    // 3D Model Dropzone
    if (document.getElementById('model-dropzone')) {
        const modelDropzone = new Dropzone('#model-dropzone', {
            url: '#',
            autoProcessQueue: false,
            maxFiles: 1,
            acceptedFiles: '.zip,application/zip',
            addRemoveLinks: true,
            dictDefaultMessage: 'Glissez-déposez votre modèle 3D (ZIP) ici ou cliquez pour sélectionner',
            dictRemoveFile: 'Supprimer',
            dictCancelUpload: 'Annuler',
            dictInvalidFileType: 'Seuls les fichiers ZIP sont autorisés',
            maxFilesize: 50, // MB
        });

        window.modelDropzone = modelDropzone;
    }

    // Reel Dropzone
    if (document.getElementById('reel-dropzone')) {
        const reelDropzone = new Dropzone('#reel-dropzone', {
            url: '#',
            autoProcessQueue: false,
            maxFiles: 1,
            acceptedFiles: 'video/*,.mp4,.mov,.ogg,.qt,.avi,.wmv,.flv,.webm',
            addRemoveLinks: true,
            dictDefaultMessage: 'Glissez-déposez votre vidéo ici ou cliquez pour sélectionner (Optionnel)',
            dictRemoveFile: 'Supprimer',
            dictCancelUpload: 'Annuler',
            dictInvalidFileType: 'Seuls les fichiers vidéo sont autorisés',
            maxFilesize: 200, // MB - Updated to match server limit
        });

        window.reelDropzone = reelDropzone;
    }
});

export default Dropzone;

