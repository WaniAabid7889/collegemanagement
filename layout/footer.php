        <footer class="footer">
            <div class="footer-container">
                <p>&copy; 2024 College Management System. All rights reserved.</p>
            </div>
        </footer>
    </div>
</div>

<style>
    .footer {
        position: fixed;
        bottom: 0;
        width: 85%;
        background-color:  #ffffff; 
        color: #00000A; 
        text-align: center;
        padding: 15px 0;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2); 
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 14px; 
    }

    .footer p {
        margin: 0;
        letter-spacing: 0.5px;
    }
    @media (max-width: 768px) {
        .footer-container {
            flex-direction: column;
            text-align: center;
        }

        .footer {
            padding: 10px 0;
        }
    }
</style>
</body>
</html>