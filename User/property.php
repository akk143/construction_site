<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Page</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <!-- property section -->
    <section class="propertyContainer">
        <h3 class="heading">Available Properties</h3>
        
        <p class="pDes">Browse our latest properties with full MMK pricing, location, size, and bedroom/bathroom details. Sold-out homes are clearly marked and cannot be purchased.</p>
        <div id="api-notice" class="property-meta" style="margin-top: 1rem; display: none; background: #fff3cd; color: #856404; padding: 1rem; border-radius: 8px; border: 1px solid #ffeaa7;"></div>
        <div id="property-loading" class="property-meta" style="margin-top: 1rem;">Loading properties...</div>
        <div id="property-error" class="property-meta" style="margin-top: 1rem; display: none;"></div>
        <div id="propertyBoxContainer" class="propertyBox-container"></div>
        <div id="propertyMetaRow" class="property-meta-row" style="display: none;">
            <div class="property-meta">
                <p id="propertySummary"></p>
            </div>
            <div id="paginationLinks" class="property-pagination-inline"></div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
    <script>
        const propertyLoading = document.getElementById('property-loading');
        const propertyError = document.getElementById('property-error');
        const propertyBoxContainer = document.getElementById('propertyBoxContainer');
        const propertyMetaRow = document.getElementById('propertyMetaRow');
        const propertySummary = document.getElementById('propertySummary');
        const paginationLinks = document.getElementById('paginationLinks');
        const apiNotice = document.getElementById('api-notice');

        const urlParams = new URLSearchParams(window.location.search);
        const page = Math.max(1, parseInt(urlParams.get('page')) || 1);
        const perPage = 3;

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function buildStatusClass(status) {
            return String(status || '')
                .trim()
                .toLowerCase()
                .replace(/\s+/g, '-');
        }

        function renderProperties(properties) {
            propertyBoxContainer.innerHTML = '';

            if (!properties.length) {
                propertyBoxContainer.innerHTML = '<div class="property-meta" style="margin-top: 1rem;">No properties are available right now. Please check back later or contact support for the latest availability.</div>';
                return;
            }

            properties.forEach(property => {
                const propertyImg = escapeHtml(property.property_profile || `https://picsum.photos/seed/property${encodeURIComponent(property.property_ID)}/500/350`);
                const effectiveStatus = property.effective_status || property.property_status || 'Available';
                const statusClass = buildStatusClass(effectiveStatus);

                const priceValue = Math.floor(Number(property.property_price) || 0);
                const areaValue = Math.floor(Number(property.property_area) || 0);
                const block = document.createElement('div');
                block.className = 'boxes';
                block.innerHTML = `
                    <div class="propertyBox enhanced-card">
                        <div class="propertyImg">
                            <div class="propertyProfile">
                                <img src="${propertyImg}" alt="${escapeHtml(property.property_name)}" loading="lazy">
                            </div>
                            <div class="p-info">
                                <span class="property-status-badge ${statusClass}">${escapeHtml(effectiveStatus)}</span>
                                <div class="property-overlay">
                                    <div class="overlay-content">
                                        <h5>${escapeHtml(property.property_name)}</h5>
                                        <p>${escapeHtml(property.property_location || 'Location unavailable')}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="propertyContent">
                            <div class="price">
                                <p><strong>${priceValue.toLocaleString('en-US')} MMK</strong></p>
                            </div>
                            <div class="location">
                                <h4>${escapeHtml(property.property_name)}</h4>
                                <p><i class="bi bi-house-door"></i> ${escapeHtml(property.property_type || 'Property')}</p>
                                <p><i class="bi bi-geo-alt"></i> ${escapeHtml(property.property_location || 'Location unavailable')}</p>
                            </div>
                            <div class="p-detail">
                                <div class="detail-item">
                                    <i class="bi bi-border-all"> Area</i>
                                    <span>${areaValue.toLocaleString('en-US')} sqft</span>
                                </div>
                                <div class="detail-item">
                                    <i class="bi bi-calendar-event"> Built Year</i>
                                    <span>${escapeHtml(property.year_built || 'N/A')}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="bi bi-clock"> Listed Date</i>
                                    <span>${escapeHtml(property.listing_date || 'Date unknown')}</span>
                                </div>
                            </div>
                            <div class="property-description">
                                <p>${escapeHtml((property.description || '').slice(0, 120))}${property.description && property.description.length > 120 ? '...' : ''}</p>
                            </div>
                            <div class="p-btn">
                                <a href="propertyDetail.php?propertyId=${encodeURIComponent(property.property_ID)}" class="btn enhanced-btn">View Details & Purchase</a>
                            </div>
                        </div>
                    </div>
                `;

                propertyBoxContainer.appendChild(block);
            });
        }

        function renderPagination(currentPage, totalPages) {
            paginationLinks.innerHTML = '';

            if (totalPages <= 1) {
                return;
            }

            const buildLink = (label, targetPage, isActive = false) => {
                const anchor = document.createElement('a');
                anchor.href = '?page=' + targetPage;
                anchor.className = 'page-link' + (isActive ? ' active' : '');
                anchor.textContent = label;
                return anchor;
            };

            const appendEllipsis = () => {
                const span = document.createElement('span');
                span.textContent = '...';
                span.className = 'page-ellipsis';
                paginationLinks.appendChild(span);
            };

            if (currentPage > 1) {
                paginationLinks.appendChild(buildLink('Previous', currentPage - 1));
            }

            const pages = [];
            pages.push(1);

            let start = Math.max(2, currentPage - 1);
            let end = Math.min(totalPages - 1, currentPage + 1);

            if (currentPage <= 4) {
                start = 2;
                end = Math.min(5, totalPages - 1);
            }

            if (currentPage >= totalPages - 3) {
                start = Math.max(totalPages - 4, 2);
                end = totalPages - 1;
            }

            if (start > 2) {
                pages.push('ellipsis-start');
            }

            for (let i = start; i <= end; i += 1) {
                pages.push(i);
            }

            if (end < totalPages - 1) {
                pages.push('ellipsis-end');
            }

            if (totalPages > 1) {
                pages.push(totalPages);
            }

            let lastPage = 0;
            pages.forEach(item => {
                if (item === 'ellipsis-start' || item === 'ellipsis-end') {
                    appendEllipsis();
                    return;
                }

                if (item === lastPage) {
                    return;
                }

                paginationLinks.appendChild(buildLink(item.toString(), item, item === currentPage));
                lastPage = item;
            });

            if (currentPage < totalPages) {
                paginationLinks.appendChild(buildLink('Next', currentPage + 1));
            }
        }

        async function loadProperties() {
            propertyLoading.style.display = 'block';
            propertyError.style.display = 'none';
            propertyMetaRow.style.display = 'none';
            apiNotice.style.display = 'none';

            try {
                const response = await fetch(`../DB/getProperties.php?page=${page}&perPage=${perPage}`);
                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Unable to load properties');
                }

                renderProperties(data.properties);

                const total = Number(data.total || 0);
                const start = total > 0 ? (page - 1) * perPage + 1 : 0;
                const end = Math.min(page * perPage, total);

                propertySummary.textContent = `Showing ${start} – ${end} of ${total} properties`;
                propertyMetaRow.style.display = 'flex';
                renderPagination(page, Number(data.totalPages || 1));

                // Show notice if using local fallback
                if (data.source === 'local') {
                    apiNotice.innerHTML = '<i class="bi bi-info-circle"></i> <strong>Note:</strong> Currently showing local properties. External property API is temporarily unavailable.';
                    apiNotice.style.display = 'block';
                } else {
                    apiNotice.style.display = 'none';
                }
            } catch (error) {
                propertyError.textContent = error.message || 'Server error. Please try again later.';
                propertyError.style.display = 'block';
            } finally {
                propertyLoading.style.display = 'none';
            }
        }

        loadProperties();
    </script>
</body>
</html>