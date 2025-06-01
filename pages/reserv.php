<?php include 'C:\xampp\htdocs\bellavista\includes\header.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initial values
    const tableData = {
        totalTables: 12,
        availableTables: 8,
        totalSeats: 46,
        availableSeats: 36,
        tables: {
            standard: {
                count: 7,
                seats: 28,
                available: 5,
                image: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&w=400'
            },
            booth: {
                count: 3,
                seats: 12,
                available: 2,
                image: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&w=400'
            },
            private: {
                count: 2,
                seats: 6,
                available: 1,
                image: 'https://images.unsplash.com/photo-1555244162-803834f70033?ixlib=rb-4.0.3&w=400'
            }
        }
    };

    // Update the availability display
    function updateAvailability() {
        // Update summary cards
        document.querySelector('.availability-card:nth-child(1) h3').textContent = tableData.totalTables;
        document.querySelector('.availability-card:nth-child(2) h3').textContent = tableData.availableTables;
        document.querySelector('.availability-card:nth-child(3) h3').textContent = tableData.totalSeats;
        
        // Update percentage bars
        document.querySelector('.availability-card:nth-child(1) .metric-bar').style.width = '100%';
        document.querySelector('.availability-card:nth-child(2) .metric-bar').style.width = `${(tableData.availableTables / tableData.totalTables) * 100}%`;
        document.querySelector('.availability-card:nth-child(3) .metric-bar').style.width = '100%';
        
        // Update table cards
        updateTableCards();
        
        // Show alert if no tables available
        if (tableData.availableTables <= 0) {
            Swal.fire({
                title: 'Fully Booked!',
                text: 'All tables are currently reserved. Please try another date or time.',
                icon: 'warning',
                confirmButtonColor: '#8B4513'
            });
        }
    }
    
    // Update table cards with current availability
    function updateTableCards() {
        for (const [type, data] of Object.entries(tableData.tables)) {
            const card = document.querySelector(`.table-card[data-type="${type}"]`);
            if (card) {
                card.querySelector('.table-count').textContent = `${data.available} of ${data.count} available`;
                card.querySelector('.table-seats').textContent = `${data.seats} total seats`;
                card.querySelector('.availability-badge').textContent = `${data.available} available`;
                
                // Disable if no availability
                const radio = card.querySelector('input[type="radio"]');
                radio.disabled = data.available <= 0;
                if (data.available <= 0) {
                    card.classList.add('unavailable');
                } else {
                    card.classList.remove('unavailable');
                }
            }
        }
    }
    
    // Handle form submission
    document.getElementById('reservation-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const guests = parseInt(document.getElementById('guests').value) || 0;
        const tableType = document.querySelector('input[name="table_type"]:checked').value;
        const table = tableData.tables[tableType];
        
        if (table.available <= 0) {
            Swal.fire({
                title: 'Table Not Available',
                text: 'This table type is no longer available. Please select another option.',
                icon: 'error',
                confirmButtonColor: '#8B4513'
            });
            return;
        }
        
        if (guests <= 0) {
            Swal.fire({
                title: 'Invalid Number of Guests',
                text: 'Please enter a valid number of guests.',
                icon: 'error',
                confirmButtonColor: '#8B4513'
            });
            return;
        }
        
        // Update availability
        tableData.availableTables--;
        table.available--;
        tableData.availableSeats -= Math.max(2, guests);
        
        // Update display
        updateAvailability();
        
        // Show success message
        Swal.fire({
            title: 'Reservation Confirmed!',
            html: `Your table for ${guests} has been reserved.<br>Confirmation sent to ${document.getElementById('email').value}`,
            icon: 'success',
            confirmButtonColor: '#8B4513'
        });
        
        // In production: this.submit();
    });
    
    // Initialize the page
    updateAvailability();
    
    // Date change handler
    document.getElementById('date').addEventListener('change', function() {
        // Simulate different availability based on day
        const date = new Date(this.value);
        const day = date.getDay();
        
        // Weekend vs weekday adjustment
        const factor = (day === 0 || day === 6) ? 0.6 : 0.8;
        
        // Update all availability
        tableData.availableTables = Math.floor(tableData.totalTables * factor);
        tableData.availableSeats = Math.floor(tableData.totalSeats * factor);
        
        for (const type in tableData.tables) {
            tableData.tables[type].available = Math.floor(tableData.tables[type].count * factor);
        }
        
        updateAvailability();
    });
});
</script>

<section class="reservation-section">
    <div class="container">
        <div class="reservation-header">
            <h2 class="section-title">Table Reservation</h2>
            <p class="section-subtitle">Book your perfect dining experience at Bella Vista</p>
        </div>
        
        <div class="availability-summary">
            <div class="availability-card">
                <i class="fas fa-table"></i>
                <div>
                    <h3>12</h3>
                    <p>Total Tables</p>
                    <div class="metric-bar" style="--percentage: 100%;"></div>
                </div>
            </div>
            <div class="availability-card">
                <i class="fas fa-calendar-check"></i>
                <div>
                    <h3>8</h3>
                    <p>Available Now</p>
                    <div class="metric-bar" style="--percentage: 67%;"></div>
                </div>
            </div>
            <div class="availability-card">
                <i class="fas fa-chair"></i>
                <div>
                    <h3>46</h3>
                    <p>Total Seats</p>
                    <div class="metric-bar" style="--percentage: 100%;"></div>
                </div>
            </div>
        </div>

        <div class="reservation-grid">
            <div class="form-container">
                <form id="reservation-form" class="elegant-form" method="POST" action="process_reservation.php">
                    <div class="form-row">
                        <div class="form-group floating">
                            <input type="text" id="name" name="name" required placeholder=" ">
                            <label for="name">Full Name</label>
                            <div class="underline"></div>
                        </div>
                        
                        <div class="form-group floating">
                            <input type="email" id="email" name="email" required placeholder=" ">
                            <label for="email">Email Address</label>
                            <div class="underline"></div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group floating">
                            <input type="tel" id="phone" name="phone" required placeholder=" ">
                            <label for="phone">Phone Number</label>
                            <div class="underline"></div>
                        </div>
                        
                        <div class="form-group floating">
                            <select id="guests" name="guests" required>
                                <option value="" disabled selected></option>
                                <option value="1">1 Person</option>
                                <option value="2">2 People</option>
                                <option value="3">3 People</option>
                                <option value="4">4 People</option>
                                <option value="5">5 People</option>
                                <option value="6">6 People</option>
                                <option value="7">7+ People</option>
                            </select>
                            <label for="guests">Number of Guests</label>
                            <div class="underline"></div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group floating">
                            <input type="date" id="date" name="date" required placeholder=" ">
                            <label for="date">Reservation Date</label>
                            <div class="underline"></div>
                        </div>
                        
                        <div class="form-group floating">
                            <select id="time" name="time" required>
                                <option value="" disabled selected></option>
                                <option value="08:00">8:00 AM</option>
                                <option value="09:00">9:00 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="12:00">12:00 PM</option>
                                <option value="13:00">1:00 PM</option>
                                <option value="14:00">2:00 PM</option>
                                <option value="15:00">3:00 PM</option>
                                <option value="16:00">4:00 PM</option>
                                <option value="17:00">5:00 PM</option>
                                <option value="18:00">6:00 PM</option>
                                <option value="19:00">7:00 PM</option>
                                <option value="20:00">8:00 PM</option>
                            </select>
                            <label for="time">Reservation Time</label>
                            <div class="underline"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="section-label">Select Your Table</label>
                        <div class="table-grid">
                            <div class="table-card" data-type="standard">
                                <input type="radio" id="table-standard" name="table_type" value="standard" checked>
                                <label for="table-standard">
                                    <div class="table-image" style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&w=400')"></div>
                                    <div class="table-details">
                                        <h4>Standard Table</h4>
                                        <div class="table-specs">
                                            <span><i class="fas fa-table"></i> <span class="table-count">7 of 12 available</span></span>
                                            <span><i class="fas fa-chair"></i> <span class="table-seats">28 seats</span></span>
                                        </div>
                                        <div class="table-desc">Perfect for couples and small groups (2-4 people)</div>
                                        <div class="availability-badge">5 available</div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="table-card" data-type="booth">
                                <input type="radio" id="table-booth" name="table_type" value="booth">
                                <label for="table-booth">
                                    <div class="table-image" style="background-image: url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&w=400')"></div>
                                    <div class="table-details">
                                        <h4>Booth Seating</h4>
                                        <div class="table-specs">
                                            <span><i class="fas fa-table"></i> <span class="table-count">3 of 12 available</span></span>
                                            <span><i class="fas fa-chair"></i> <span class="table-seats">12 seats</span></span>
                                        </div>
                                        <div class="table-desc">Comfortable semi-private seating (4-6 people)</div>
                                        <div class="availability-badge">2 available</div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="table-card" data-type="private">
                                <input type="radio" id="table-private" name="table_type" value="private">
                                <label for="table-private">
                                    <div class="table-image" style="background-image: url('https://images.unsplash.com/photo-1555244162-803834f70033?ixlib=rb-4.0.3&w=400')"></div>
                                    <div class="table-details">
                                        <h4>Private Room</h4>
                                        <div class="table-specs">
                                            <span><i class="fas fa-table"></i> <span class="table-count">2 of 12 available</span></span>
                                            <span><i class="fas fa-chair"></i> <span class="table-seats">6 seats</span></span>
                                        </div>
                                        <div class="table-desc">Exclusive VIP area for special occasions (6-10 people)</div>
                                        <div class="availability-badge">1 available</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group floating">
                        <textarea id="special-requests" name="special_requests" placeholder=" " rows="4"></textarea>
                        <label for="special-requests">Special Requests</label>
                        <div class="underline"></div>
                    </div>
                    
                    <div class="form-footer">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-calendar-check"></i> Confirm Reservation
                        </button>
                        <p class="confirmation-note">
                            <i class="fas fa-info-circle"></i> You'll receive a confirmation email within 15 minutes
                        </p>
                    </div>
                </form>
            </div>
            
            <div class="restaurant-preview">
                <div class="preview-image" style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&w=800')">
                    <div class="preview-overlay">
                        <h3>Experience Bella Vista</h3>
                        <p>Elegant atmosphere • Premium service • Memorable dining</p>
                    </div>
                </div>
                <div class="preview-features">
                    <div class="feature">
                        <i class="fas fa-wine-glass-alt"></i>
                        <span>Premium beverages</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-utensils"></i>
                        <span>Gourmet cuisine</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-parking"></i>
                        <span>Valet parking</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>