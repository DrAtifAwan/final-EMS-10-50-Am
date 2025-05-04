<style>
    /* edit_event.css */
/* ======================== */
/* General Styles           */
/* ======================== */
body {
    background-color: #f8f9fa;
    font-family: 'Poppins', sans-serif;
    color: #2c3e50;
}

.container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(106, 17, 203, 0.1);
}

/* ======================== */
/* Heading Styles           */
/* ======================== */
h1 {
    color: #6a11cb;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 2rem;
    text-align: center;
}

/* ======================== */
/* Form Styles              */
/* ======================== */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    color: #6a11cb;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #6a11cb;
    box-shadow: 0 0 8px rgba(106, 17, 203, 0.2);
}

/* ======================== */
/* File Upload Styles       */
/* ======================== */
.custom-file-input {
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.custom-file-input input[type="file"] {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    cursor: pointer;
}

.file-upload-label {
    display: block;
    padding: 12px;
    border: 2px dashed #e9ecef;
    border-radius: 10px;
    text-align: center;
    color: #6c757d;
    transition: all 0.3s ease;
}

.file-upload-label:hover {
    border-color: #6a11cb;
    background: #f3e5ff;
}

/* ======================== */
/* Button Styles            */
/* ======================== */
.btn-primary {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    border: none;
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    width: 100%;
    margin-top: 1rem;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(106, 17, 203, 0.2);
}

.btn-secondary {
    border: 2px solid #6a11cb;
    color: #6a11cb;
    padding: 10px 25px;
    border-radius: 50px;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-secondary:hover {
    background: #6a11cb;
    color: white;
}

/* ======================== */
/* Responsive Design        */
/* ======================== */
@media (max-width: 768px) {
    .container {
        margin: 1rem;
        padding: 1.5rem;
    }
    
    h1 {
        font-size: 2rem;
    }
    
    .form-control {
        padding: 10px 12px;
    }
}
</style>