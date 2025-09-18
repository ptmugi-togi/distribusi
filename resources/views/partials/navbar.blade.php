<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="/">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Master</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="master-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <?php if(auth()->user()->level=="IT"){ ?>
            <li class="{{ request()->is('register') ? 'active' : '' }}">
                <a href="/register">
                <i class="bi bi-circle"></i><span>REGISTER</span>
                </a>
            </li>
            <?php } ?>
            <li>
                <a href="/ssgrup">
                <i class="bi bi-circle"></i><span>MSSGRUP</span>
                </a>
            </li>
            <li>
                <a href="/msgrup">
                <i class="bi bi-circle"></i><span>MSGRUP</span>
                </a>
            </li>
            <li>
                <a href="/mpgrup">
                <i class="bi bi-circle"></i><span>MPGRUP</span>
                </a>
            </li>
            <li>
                <a href="/mbrand">
                <i class="bi bi-circle"></i><span>MBRAND</span>
                </a>
            </li>
            <li>
                <a href="/mitype">
                <i class="bi bi-circle"></i><span>MITYPE</span>
                </a>
            </li>
            <li>
                <a href="/mcls">
                <i class="bi bi-circle"></i><span>MCLS</span>
                </a>
            </li>
            <li>
                <a href="/mpromas">
                <i class="bi bi-circle"></i><span>MPROMAS</span>
                </a>
            </li>
            <li>
                <a href="/msreno">
                <i class="bi bi-circle"></i><span>MSRENO</span>
                </a>
            </li>
            <li>
                <a href="/mcindu">
                <i class="bi bi-circle"></i><span>MCINDU</span>
                </a>
            </li>
            <li>
                <a href="/mstmas">
                <i class="bi bi-circle"></i><span>MSTMAS</span>
                </a>
            </li>
            <li>
                <a href="/mbranch">
                <i class="bi bi-circle"></i><span>MBRANCH</span>
                </a>
            </li>
            <li>
                <a href="/mdepo">
                <i class="bi bi-circle"></i><span>MDEPO</span>
                </a>
            </li>
            <li>
                <a href="/cusmas">
                <i class="bi bi-circle"></i><span>CUSMAS</span>
                </a>
            </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="/roce">
              <i class="bi bi-circle"></i><span>RETAIL ORDER CONFIRMATION ENTRY</span>
            </a>
          </li>
          {{-- <li>
            <a href="forms-layouts.html">
              <i class="bi bi-circle"></i><span>Form Layouts</span>
            </a>
          </li>
          <li>
            <a href="forms-editors.html">
              <i class="bi bi-circle"></i><span>Form Editors</span>
            </a>
          </li>
          <li>
            <a href="forms-validation.html">
              <i class="bi bi-circle"></i><span>Form Validation</span>
            </a>
          </li> --}}
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#purchasing-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-cart"></i><span>Purchasing</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="purchasing-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="/tpohdr">
            <i class="bi bi-circle"></i><span>PO</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tables-general.html">
              <i class="bi bi-circle"></i><span>General Tables</span>
            </a>
          </li>
          <li>
            <a href="tables-data.html">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Charts</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="#">
              <i class="bi bi-circle"></i><span>Chart.js</span>
            </a>
          </li>
          <li>
            <a href="charts-apexcharts.html">
              <i class="bi bi-circle"></i><span>ApexCharts</span>
            </a>
          </li>
          <li>
            <a href="charts-echarts.html">
              <i class="bi bi-circle"></i><span>ECharts</span>
            </a>
          </li>
        </ul>
      </li>

    </ul>

  </aside>
